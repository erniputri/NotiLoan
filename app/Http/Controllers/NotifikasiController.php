<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Services\NotificationDispatchService;
use App\Services\NotificationScheduleService;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function __construct(
        private readonly NotificationScheduleService $notificationScheduleService,
        private readonly NotificationDispatchService $notificationDispatchService
    ) {
    }

    // Ringkasan notifikasi dihitung dari keseluruhan data, sedangkan tabel tetap mengikuti filter dan pagination.
    public function index(Request $request)
    {
        $search = $request->search;
        $pendingNotificationCount = Peminjaman::where('pokok_sisa', '>', 0)
            ->where(function ($query) {
                $query->whereDoesntHave('notifikasi')
                    ->orWhereHas('notifikasi', function ($subQuery) {
                        $subQuery->where('status', false);
                    });
            })
            ->count();

        $sentNotificationCount = Peminjaman::where('pokok_sisa', '>', 0)
            ->whereHas('notifikasi', function ($query) {
                $query->where('status', true);
            })
            ->count();

        $dataPeminjaman = Peminjaman::with(['notifikasi', 'latestPembayaran'])
            ->where('pokok_sisa', '>', 0)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama_mitra', 'like', "%{$search}%")
                        ->orWhere('kontak', 'like', "%{$search}%");
                });
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

    // Kirim manual memastikan mitra memang sudah jatuh tempo, belum bayar, dan belum lunas.
    public function send($id)
    {
        $peminjaman = Peminjaman::with(['notifikasi', 'latestPembayaran'])->findOrFail($id);

        if ((int) $peminjaman->pokok_sisa <= 0) {
            return back()->with('info', 'Pinjaman sudah lunas sehingga tidak perlu dikirim notifikasi lagi.');
        }

        if (! $this->notificationScheduleService->isLoanDueAndUnpaid($peminjaman)) {
            return back()->with('info', 'Notifikasi hanya dikirim ke mitra yang sudah jatuh tempo dan belum membayar.');
        }

        $notif = $this->notificationScheduleService->syncForLoan($peminjaman);

        if (! $notif) {
            return back()->with('info', 'Notifikasi belum bisa disiapkan untuk mitra ini.');
        }

        if ($notif->status && $notif->sent_at && $notif->sent_at->isSameMonth(now())) {
            return back()->with('info', 'Notifikasi bulan ini sudah terkirim.');
        }

        $attempt = $this->notificationDispatchService->dispatch($notif, 'manual');

        return back()->with('success', "WhatsApp berhasil diproses. Attempt tercatat dengan ID {$attempt->id}.");
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
