<?php
namespace App\Http\Controllers;

use App\Http\Requests\Pembayaran\StorePembayaranRequest;
use App\Http\Requests\Pembayaran\UpdatePembayaranRequest;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Services\PembayaranService;
use Illuminate\Validation\ValidationException;

class PembayaranController extends Controller
{
    public function __construct(
        private readonly PembayaranService $pembayaranService
    ) {
    }

    // Index pembayaran fokus pada list transaksi dan filter nama mitra agar pencarian cepat dilakukan.
    public function index(\Illuminate\Http\Request $request)
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

    // Form create hanya menawarkan pinjaman yang masih punya sisa pokok untuk dibayar.
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

    // Controller hanya mengatur flow request, sedangkan hitung saldo dan transaksi dijalankan oleh service.
    public function store(StorePembayaranRequest $request)
    {
        $validated = $request->validated();

        $peminjaman = Peminjaman::findOrFail($validated['peminjaman_id']);

        try {
            $this->pembayaranService->create($peminjaman, $validated);
        } catch (ValidationException $exception) {
            if ($exception->errors()['payment_window'] ?? false) {
                return back()
                    ->withInput()
                    ->with('reminder', true);
            }

            throw $exception;
        }

        return redirect()
            ->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil disimpan');
    }

    // Detail pembayaran dipisah ke halaman sendiri agar bukti bayar dan data mitra bisa dilihat lebih lengkap.
    public function show($id)
    {
        $pembayaran = Pembayaran::with('peminjaman')
            ->findOrFail($id);

        return view('pages.pembayaran.show', compact('pembayaran'));
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        return view('pages.pembayaran.edit', compact('pembayaran'));
    }

    // Update pembayaran berisiko memengaruhi saldo pinjaman, jadi logikanya tidak ditaruh di controller.
    public function update(UpdatePembayaranRequest $request, $id)
    {
        $validated = $request->validated();

        $pembayaran = Pembayaran::findOrFail($id);
        $this->pembayaranService->update($pembayaran, $validated);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil diperbarui');
    }

    // Hapus pembayaran harus ikut memulihkan saldo pinjaman, sehingga aman bila lewat service.
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $this->pembayaranService->delete($pembayaran);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus');
    }
}
