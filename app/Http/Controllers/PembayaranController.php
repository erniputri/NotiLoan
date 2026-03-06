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

        //cek pembayaran 30 hari
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

        //validasi peminjaman
        if ($request->jumlah_bayar > $peminjaman->pokok_sisa) {
            return back()->withErrors([
                'jumlah_bayar' => 'Jumlah bayar melebihi sisa pinjaman',
            ]);
        }

        //upload bukti pembayaran
        $path = null;

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');
        }

        Pembayaran::create([
            'peminjaman_id'      => $peminjaman->id,
            'tanggal_pembayaran' => $request->tanggal_pembayaran,
            'jumlah_bayar'       => $request->jumlah_bayar,
            'bukti_pembayaran'   => $path,
        ]);

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

    public function show($id)
    {
        $pembayaran = Pembayaran::with('peminjaman')
            ->findOrFail($id);

        return view('pages.pembayaran.show', compact('pembayaran'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $peminjaman = Peminjaman::where('pokok_sisa', '>', 0)->get();

        return view('pages.pembayaran.edit', compact('pembayaran', 'peminjaman'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'jumlah_bayar'       => 'required|numeric|min:1',
            'bukti_pembayaran'   => 'nullable|file|mimes:jpg,jpeg,png,pdf',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $peminjaman = $pembayaran->peminjaman;

        $peminjaman->pokok_sisa          += $pembayaran->jumlah_bayar;
        $peminjaman->lama_angsuran_bulan += 1;

        if ($request->jumlah_bayar > $peminjaman->pokok_sisa) {
            return back()->withErrors([
                'jumlah_bayar' => 'Jumlah melebihi sisa pinjaman',
            ]);
        }

        // Upload bukti baru
        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')
                ->store('bukti-pembayaran', 'public');

            $pembayaran->bukti_pembayaran = $path;
        }

        $pembayaran->tanggal_pembayaran = $request->tanggal_pembayaran;
        $pembayaran->jumlah_bayar       = $request->jumlah_bayar;
        $pembayaran->save();

        $peminjaman->pokok_sisa          -= $request->jumlah_bayar;
        $peminjaman->lama_angsuran_bulan = max(0, $peminjaman->lama_angsuran_bulan - 1);
        $peminjaman->save();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $peminjaman = $pembayaran->peminjaman;

        // Kembalikan saldo & bulan
        $peminjaman->pokok_sisa          += $pembayaran->jumlah_bayar;
        $peminjaman->lama_angsuran_bulan += 1;
        $peminjaman->save();

        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus');
    }
}
