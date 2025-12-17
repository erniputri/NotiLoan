<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['dataPeminjaman'] = Peminjaman::all();
        return view('pages.data.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.data.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $data['nama'] = $request->nama;
        $data['kontak'] = $request->kontak;
        $data['tgl_peminjaman'] = $request->tgl_peminjaman;
        $data['tgl_pengembalian'] = $request->tgl_pengembalian;
        $data['jumlah'] = $request->jumlah;

        $peminjaman = Peminjaman::create($data);

        // hitung tanggal notifikasi (H-30)
        // $tanggalNotifikasi = Carbon::parse($peminjaman->tgl_pengembalian)
        //     ->subDays(30);
        $tanggalNotifikasi = now()->subMinute();


        // simpan ke tabel notifications
        Notification::create([
            'kontak' => $peminjaman->kontak,
            'message' => 'Yth ' . $peminjaman->nama .
                ', pinjaman Anda akan jatuh tempo pada ' .
                $peminjaman->tgl_pengembalian,
            'send_at' => $tanggalNotifikasi,
            'status' => 0
        ]);

        return redirect()->route('data.index')->with('tambah', 'Penambahan Data Berhasil');
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
        $data['dataPeminjaman'] = Peminjaman::findOrFail($id);
        return view('pages.data.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $peminjaman_id = $id;
        $peminjaman = Peminjaman::findOrFail($peminjaman_id);
        $peminjaman->nama = $request->nama;
        $peminjaman->kontak = $request->kontak;
        $peminjaman->tgl_peminjaman = $request->tgl_peminjaman;
        $peminjaman->tgl_pengembalian = $request->tgl_pengembalian;
        $peminjaman->jumlah = $request->jumlah;

        $peminjaman->save();
        return redirect()->route('data.index')->with('success', 'Perubahan berhasil di simpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->delete();
        return redirect()->route('data.index')->with('hapus', 'Data berhasil dihapus');
    }
}
