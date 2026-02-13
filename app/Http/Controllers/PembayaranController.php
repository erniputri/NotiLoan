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

        $pembayaran = $query->latest()->paginate(10)
            ->withQueryString();

        return view('pages.pembayaran.index', compact('pembayaran'));
    }

    public function create()
    {
        $peminjaman = Peminjaman::select(
            'id',
            'nama_mitra',
            'pokok_sisa'
        )->where('pokok_sisa', '>', 0)->get();

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

        if ($request->jumlah_bayar > $peminjaman->pokok_sisa) {
            return back()->withErrors([
                'jumlah_bayar' => 'Jumlah bayar melebihi sisa pinjaman',
            ]);
        }

        // UPLOAD FILE
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

        // UPDATE SISA PINJAMAN
        $peminjaman->update([
            'pokok_sisa' => $peminjaman->pokok_sisa - $request->jumlah_bayar,
        ]);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan');
    }
}
