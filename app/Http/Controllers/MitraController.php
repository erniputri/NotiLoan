<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mitra\UpdateMitraRequest;
use App\Models\Mitra;
use App\Models\NotificationAttempt;
use App\Models\Pembayaran;
use App\Services\MitraService;
use Illuminate\Http\Request;

class MitraController extends Controller
{
    public function __construct(
        private readonly MitraService $mitraService
    ) {
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $loanStatus = $request->loan_status;

        $mitras = Mitra::query()
            ->select([
                'id',
                'nomor_mitra',
                'nama_mitra',
                'kontak',
                'kabupaten',
            ])
            ->with('latestPeminjaman')
            ->withCount('peminjaman')
            ->withCount(['peminjaman as active_loan_count' => fn ($query) => $query->where('pokok_sisa', '>', 0)])
            ->withSum('peminjaman as total_pinjaman', 'pokok_pinjaman_awal')
            ->withSum('peminjaman as total_sisa', 'pokok_sisa')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama_mitra', 'like', "%{$search}%")
                        ->orWhere('nomor_mitra', 'like', "%{$search}%")
                        ->orWhere('kontak', 'like', "%{$search}%")
                        ->orWhere('kabupaten', 'like', "%{$search}%");
                });
            })
            ->when($loanStatus === 'aktif', function ($query) {
                $query->whereHas('peminjaman', fn ($subQuery) => $subQuery->where('pokok_sisa', '>', 0));
            })
            ->when($loanStatus === 'lunas', function ($query) {
                $query->whereHas('peminjaman')
                    ->whereDoesntHave('peminjaman', fn ($subQuery) => $subQuery->where('pokok_sisa', '>', 0));
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalMitra = Mitra::count();
        $mitraAktif = Mitra::whereHas('peminjaman', fn ($query) => $query->where('pokok_sisa', '>', 0))->count();
        $totalOutstanding = (int) \App\Models\Peminjaman::sum('pokok_sisa');

        return view('pages.mitra.index', compact(
            'mitras',
            'search',
            'loanStatus',
            'totalMitra',
            'mitraAktif',
            'totalOutstanding'
        ));
    }

    public function show($id)
    {
        $mitra = Mitra::with('latestPeminjaman')
            ->withCount('peminjaman')
            ->findOrFail($id);

        $riwayatPinjaman = $mitra->peminjaman()
            ->with(['latestPembayaran', 'notifikasi'])
            ->latest('tgl_peminjaman')
            ->paginate(5, ['*'], 'loan_page')
            ->withQueryString();

        $riwayatPembayaran = Pembayaran::with('peminjaman')
            ->whereHas('peminjaman', fn ($query) => $query->where('mitra_id', $mitra->id))
            ->latest('tanggal_pembayaran')
            ->paginate(5, ['*'], 'payment_page')
            ->withQueryString();

        $riwayatNotifikasi = NotificationAttempt::with('peminjaman')
            ->whereHas('peminjaman', fn ($query) => $query->where('mitra_id', $mitra->id))
            ->latest('attempted_at')
            ->latest('id')
            ->paginate(5, ['*'], 'notification_page')
            ->withQueryString();

        $totalPinjaman = (int) $mitra->peminjaman()->sum('pokok_pinjaman_awal');
        $totalSisa = (int) $mitra->peminjaman()->sum('pokok_sisa');
        $totalTerbayar = $totalPinjaman - $totalSisa;

        return view('pages.mitra.show', compact(
            'mitra',
            'riwayatPinjaman',
            'riwayatPembayaran',
            'riwayatNotifikasi',
            'totalPinjaman',
            'totalSisa',
            'totalTerbayar'
        ));
    }

    public function edit($id)
    {
        $mitra = Mitra::findOrFail($id);
        $virtualAccountBanks = \App\Models\Peminjaman::virtualAccountBankOptions();

        return view('pages.mitra.edit', compact('mitra', 'virtualAccountBanks'));
    }

    public function update(UpdateMitraRequest $request, $id)
    {
        $mitra = Mitra::findOrFail($id);
        $this->mitraService->updateMitra($mitra, $request->validated());

        return redirect()
            ->route('mitra.show', $mitra->id)
            ->with('success', 'Data mitra berhasil diperbarui.');
    }
}
