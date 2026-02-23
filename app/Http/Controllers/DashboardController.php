<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalData = Peminjaman::count();

        $totalNotifikasi = Notification::count();

        $jatuhTempo30Hari = Peminjaman::whereBetween(
            'tgl_jatuh_tempo',
            [now()->startOfDay(), now()->copy()->addMonth()->endOfDay()]
        )->count();

        $jatuhTempoList = Peminjaman::where('pokok_sisa', '>', 0) // ⬅️ HANYA YANG BELUM LUNAS
            ->whereBetween('tgl_jatuh_tempo', [now(), now()->addDays(30)])
            ->orderBy('tgl_jatuh_tempo')
            ->limit(5)
            ->get();

        // === DATA GRAFIK KUALITAS KREDIT ===
        $chartData = Peminjaman::selectRaw('kualitas_kredit, COUNT(*) as total')
            ->groupBy('kualitas_kredit')
            ->pluck('total', 'kualitas_kredit');

        return view('pages.dashboard', compact(
            'totalData',
            'totalNotifikasi',
            'jatuhTempo30Hari',
            'jatuhTempoList',
            'chartData'
        ));
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
    public function show($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.data.show', compact('peminjaman'));
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
