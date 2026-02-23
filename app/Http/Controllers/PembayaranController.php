<?php
namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pembayaran::with('peminjaman');

        if ($request->search) {
            $query->whereHas('peminjaman', function ($q) use ($request) {
                $q->where('nama_mitra', 'like', '%' . $request->search . '%');
            });
        }

        $pembayaran = $query->latest()->paginate(10)->withQueryString();

        return view('pages.pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::select(
            'id',
            'nama_mitra',
            'pokok_sisa',
            'lama_angsuran_bulan'
        )
            ->where('pokok_sisa', '>', 0)
            ->get();

        return view('pages.pembayaran.create', compact('peminjaman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id'      => 'required|exists:peminjaman,id',
            'tanggal_pembayaran' => 'required|date',
            'jumlah_bayar'       => 'required|numeric|min:1',
            'bukti_pembayaran'   => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        // =====================================
        // CEK PEMBAYARAN 30 HARI TERAKHIR
        // =====================================

        $pembayaranTerakhir = $peminjaman->pembayaran()
            ->latest('tanggal_pembayaran')
            ->first();

        if ($pembayaranTerakhir && ! $request->has('force')) {

            $selisihHari = now()->diffInDays(
                $pembayaranTerakhir->tanggal_pembayaran
            );

            if ($selisihHari <= 30) {
                return back()
                    ->withInput()
                    ->with('reminder', true);
            }
        }

        // =====================================
        // VALIDASI SISA PINJAMAN
        // =====================================

        if ($request->jumlah_bayar > $peminjaman->pokok_sisa) {
            return back()->withErrors([
                'jumlah_bayar' => 'Jumlah bayar melebihi sisa pinjaman',
            ]);
        }

        // =====================================
        // UPLOAD BUKTI
        // =====================================

        $path = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');
        }

        // =====================================
        // SIMPAN PEMBAYARAN
        // =====================================

        Pembayaran::create([
            'peminjaman_id'      => $peminjaman->id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'jumlah_bayar'       => $request->jumlah_bayar,
            'bukti_pembayaran'   => $path,
        ]);

        // =====================================
        // UPDATE POKOK & BULAN
        // =====================================

        $peminjaman->pokok_sisa -= $request->jumlah_bayar;

        // Kurangi 1 bulan setiap transaksi
        $peminjaman->lama_angsuran_bulan = max(
            0,
            $peminjaman->lama_angsuran_bulan - 1
        );

        $peminjaman->save();

        return redirect()
            ->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan');
    }
}
