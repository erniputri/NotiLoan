<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Notification;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dataPeminjaman = Peminjaman::with('notifikasi')->get();

        return view('pages.notifikasi.index', compact('dataPeminjaman'));
    }

    public function send($id)
    {
        $peminjaman = Peminjaman::with('notifikasi')->findOrFail($id);

        if (! $peminjaman->notifikasi) {
            return back()->with('error', 'Notifikasi belum tersedia.');
        }

        $notif = $peminjaman->notifikasi;

        if ($notif->status == 1) {
            return back()->with('info', 'Notifikasi sudah terkirim.');
        }

        // =========================
        // SIMULASI KIRIM WA
        // =========================
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
