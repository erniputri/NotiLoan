<?php
namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalData       = Peminjaman::count();
        $totalNotifikasi = Notification::count();

        // ==============================
        // REMINDER CICILAN BULANAN
        // ==============================
        $jatuhTempoList = Peminjaman::where('pokok_sisa', '>', 0)
            ->get()
            ->map(function ($peminjaman) {

                $pembayaranTerakhir = $peminjaman->pembayaran()
                    ->latest('tanggal_pembayaran')
                    ->first();

                if (! $pembayaranTerakhir) {
                    $jatuhTempoBulanan = Carbon::parse($peminjaman->tgl_peminjaman)->addMonth();
                } else {
                    $jatuhTempoBulanan = Carbon::parse($pembayaranTerakhir->tanggal_pembayaran)->addMonth();
                }

                $peminjaman->jatuh_tempo_bulanan = $jatuhTempoBulanan;

                return $peminjaman;
            })
            ->filter(function ($peminjaman) {
                return now()->greaterThanOrEqualTo($peminjaman->jatuh_tempo_bulanan);
            });

        $jatuhTempo30Hari = $jatuhTempoList->count();

        // ==============================
        // GRAFIK KUALITAS KREDIT
        // ==============================
        $chartData = Peminjaman::all()
            ->groupBy('kualitas_kredit')
            ->map->count();

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
