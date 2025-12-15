<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

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

        Peminjaman::create($data);

        return redirect()->route('data.index')->with('success', 'Penambahan Data Berhasil');
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
