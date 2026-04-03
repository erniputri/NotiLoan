<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\NotificationScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotifikasiController extends Controller
{
    public function __construct(
        private readonly NotificationScheduleService $notificationScheduleService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $pendingNotificationCount = Peminjaman::whereDoesntHave('notifikasi')
            ->orWhereHas('notifikasi', function ($query) {
                $query->where('status', false);
            })
            ->count();

        $sentNotificationCount = Peminjaman::whereHas('notifikasi', function ($query) {
            $query->where('status', true);
        })->count();

        $dataPeminjaman = Peminjaman::with('notifikasi')
            ->when($search, function ($query) use ($search) {
                $query->where('nama_mitra', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.notifikasi.index', compact(
            'dataPeminjaman',
            'search',
            'pendingNotificationCount',
            'sentNotificationCount'
        ));
    }

    public function send($id)
    {
        $peminjaman = Peminjaman::with('notifikasi')->findOrFail($id);

        if (! $peminjaman->notifikasi) {
            $this->notificationScheduleService->syncForLoan($peminjaman);
            $peminjaman->load('notifikasi');
        }

        $notif = $peminjaman->notifikasi;

        if ($notif->status == 1) {
            return back()->with('info', 'Notifikasi sudah terkirim.');
        }

        //simulasi kirim wa
        Log::info('WA TERKIRIM MANUAL', [
            'ke'      => $notif->kontak,
            'message' => $notif->message,
        ]);

        // Update status
        $notif->update([
            'status'  => 1,
            'sent_at' => now(),
        ]);

        return back()->with('success', 'WhatsApp berhasil dikirim.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
