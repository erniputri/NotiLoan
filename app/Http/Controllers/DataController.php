<?php
namespace App\Http\Controllers;

use App\Exports\PeminjamanExport;
use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataController extends Controller
{
    /* =========================
     * INDEX
     * ========================= */
    public function index()
    {
        $dataPeminjaman = Peminjaman::latest()->get();
        return view('pages.data.index', compact('dataPeminjaman'));
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.data.show', compact('peminjaman'));
    }

    public function exportExcel()
    {
        return Excel::download(
            new PeminjamanExport,
            'Data-NotiLoan.xlsx'
        );
    }

    /* =========================
     * STEP 1 – DATA MITRA
     * ========================= */
    public function createStep1()
    {
        return view('pages.data.create-step-1');
    }

    public function storeStep1(Request $request)
    {
        $request->validate([
            'nama_mitra' => 'required',
            'kontak'     => 'required',
        ]);

        session([
            'peminjaman.step1' => $request->only([
                'nomor_mitra',
                'virtual_account',
                'nama_mitra',
                'kontak',
                'alamat',
                'kabupaten',
                'sektor',
            ]),
        ]);

        return redirect()->route('data.create.step2');
    }

    /* =========================
     * STEP 2 – DATA PINJAMAN
     * ========================= */
    public function createStep2()
    {
        if (! session()->has('peminjaman.step1')) {
            return redirect()->route('data.create.step1');
        }

        return view('pages.data.create-step-2');
    }

    public function storeStep2(Request $request)
    {
        $request->validate([
            'pokok_pinjaman_awal' => 'required|numeric',
            'tgl_peminjaman'      => 'required|date',
            'lama_angsuran_bulan' => 'required|numeric',
        ]);

        $lama = (int) $request->lama_angsuran_bulan;

        // LOGIKA BUNGA
        $bunga = 6;
        if ($request->pokok_pinjaman_awal > 10000000) {
            $bunga = 8;
        }

        if ($request->pokok_pinjaman_awal > 25000000) {
            $bunga = 10;
        }

        session([
            'peminjaman.step2' => [
                'pokok_pinjaman_awal' => $request->pokok_pinjaman_awal,
                'tgl_peminjaman'      => $request->tgl_peminjaman,
                'lama_angsuran_bulan' => $lama,
                'bunga_persen'        => $bunga,
                'tgl_jatuh_tempo'     => Carbon::parse($request->tgl_peminjaman)
                    ->addMonths($lama)
                    ->format('Y-m-d'),
                'tgl_akhir_pinjaman'  => Carbon::parse($request->tgl_peminjaman)
                    ->addMonths($lama)
                    ->format('Y-m-d'),
            ],
        ]);

        return redirect()->route('data.create.step3');
    }

    /* =========================
     * STEP 3 – ADMIN & JAMINAN
     * ========================= */
    public function createStep3()
    {
        if (! session()->has('peminjaman.step2')) {
            return redirect()->route('data.create.step1');
        }

        return view('pages.data.create-step-3');
    }

    /* =========================
     * FINAL INSERT (1x INSERT)
     * ========================= */
    public function storeFinal(Request $request)
    {
        $request->validate([
            'administrasi_awal'   => 'required|numeric',
            'no_surat_perjanjian' => 'required',
            'jaminan'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $step1 = session('peminjaman.step1');
        $step2 = session('peminjaman.step2');

        if (! $step1 || ! $step2) {
            return redirect()
                ->route('data.create.step1')
                ->with('error', 'Session habis, silakan ulangi input.');
        }

        // Upload jaminan
        $pathJaminan = $request->file('jaminan')->store('jaminan', 'public');

        // INSERT DATA PEMINJAMAN
        $peminjaman = Peminjaman::create([
            // STEP 1
            'nomor_mitra'         => $step1['nomor_mitra'] ?? null,
            'virtual_account'     => $step1['virtual_account'] ?? null,
            'nama_mitra'          => $step1['nama_mitra'],
            'kontak'              => $step1['kontak'],
            'alamat'              => $step1['alamat'] ?? null,
            'kabupaten'           => $step1['kabupaten'] ?? null,
            'sektor'              => $step1['sektor'] ?? null,

            // STEP 2
            'tgl_peminjaman'      => $step2['tgl_peminjaman'],
            'tgl_jatuh_tempo'     => $step2['tgl_jatuh_tempo'],
            'tgl_akhir_pinjaman'  => $step2['tgl_akhir_pinjaman'],
            'lama_angsuran_bulan' => $step2['lama_angsuran_bulan'],
            'bunga_persen'        => $step2['bunga_persen'],
            'pokok_pinjaman_awal' => $step2['pokok_pinjaman_awal'],

            // STEP 3
            'administrasi_awal'   => $request->administrasi_awal,
            'no_surat_perjanjian' => $request->no_surat_perjanjian,
            'jaminan'             => $pathJaminan,

            // DEFAULT
            'pokok_cicilan_sd'    => 0,
            'jasa_cicilan_sd'     => 0,
            'pokok_sisa'          => $step2['pokok_pinjaman_awal'],
            'jasa_sisa'           => $request->administrasi_awal,
            'kualitas_kredit'     => 'Lancar',
        ]);

        // INSERT NOTIFIKASI
        Notification::create([
            'peminjaman_id' => $peminjaman->id,
            'kontak'        => $peminjaman->kontak,
            'message'       => 'Yth ' . $peminjaman->nama_mitra .
            ', pinjaman Anda akan jatuh tempo pada ' .
            $peminjaman->tgl_jatuh_tempo,
            'send_at'       => now(),
            'status'        => 0,
        ]);

        // Bersihkan session wizard
        session()->forget('peminjaman');

        return redirect()
            ->route('data.index')
            ->with('tambah', 'Data berhasil ditambahkan');
    }

    public function editStep1($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.data.edit-step-1', compact('peminjaman'));
    }

    public function updateStep1(Request $request, $id)
    {
        $request->validate([
            'nama_mitra' => 'required',
            'kontak'     => 'required',
        ]);

        Peminjaman::findOrFail($id)->update([
            'nomor_mitra'     => $request->nomor_mitra,
            'virtual_account' => $request->virtual_account,
            'nama_mitra'      => $request->nama_mitra,
            'kontak'          => $request->kontak,
            'alamat'          => $request->alamat,
            'kabupaten'       => $request->kabupaten,
            'sektor'          => $request->sektor,
        ]);

        return redirect()->route('data.edit.step2', $id);
    }

    public function editStep2($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.data.edit-step-2', compact('peminjaman'));
    }

    public function updateStep2(Request $request, $id)
    {
        $request->validate([
            'pokok_pinjaman_awal' => 'required|numeric',
            'tgl_peminjaman'      => 'required|date',
            'lama_angsuran_bulan' => 'required|numeric',
        ]);

        $lama = (int) $request->lama_angsuran_bulan;

        $tglJatuhTempo = \Carbon\Carbon::parse($request->tgl_peminjaman)
            ->addMonths($lama)
            ->format('Y-m-d');

        Peminjaman::findOrFail($id)->update([
            'pokok_pinjaman_awal' => $request->pokok_pinjaman_awal,
            'tgl_peminjaman'      => $request->tgl_peminjaman,
            'lama_angsuran_bulan' => $lama,
            'tgl_jatuh_tempo'     => $tglJatuhTempo,
        ]);

        return redirect()->route('data.edit.step3', $id);
    }

    public function editStep3($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('pages.data.edit-step-3', compact('peminjaman'));
    }

    public function updateStep3(Request $request, $id)
    {
        $request->validate([
            'administrasi_awal' => 'required|numeric',
            'kualitas_kredit'   => 'required',
            'jaminan'           => 'nullable|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        $data = [
            'administrasi_awal' => $request->administrasi_awal,
            'kualitas_kredit'   => $request->kualitas_kredit,
        ];

        if ($request->hasFile('jaminan')) {
            $data['jaminan'] = $request->file('jaminan')->store('jaminan', 'public');
        }

        $peminjaman->update($data);

        return redirect()
            ->route('data.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    public function edit(string $id)
    {
        $dataPeminjaman = Peminjaman::findOrFail($id);
        return view('pages.data.edit', compact('dataPeminjaman'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_mitra'          => 'required',
            'kontak'              => 'required',
            'tgl_peminjaman'      => 'required|date',
            'lama_angsuran_bulan' => 'required|numeric',
            'pokok_pinjaman_awal' => 'required|numeric',
            'sektor'              => 'required',
            'kualitas_kredit'     => 'required',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);

        $lama = (int) $request->lama_angsuran_bulan;

        // hitung ulang tanggal jatuh tempo
        $tglJatuhTempo = \Carbon\Carbon::parse($request->tgl_peminjaman)
            ->addMonths($lama)
            ->format('Y-m-d');

        $peminjaman->update([
            // DATA MITRA
            'nama_mitra'          => $request->nama_mitra,
            'kontak'              => $request->kontak,
            'alamat'              => $request->alamat,
            'kabupaten'           => $request->kabupaten,
            'sektor'              => $request->sektor,
            'kualitas_kredit'     => $request->kualitas_kredit,

            // DATA PINJAMAN
            'tgl_peminjaman'      => $request->tgl_peminjaman,
            'lama_angsuran_bulan' => $lama,
            'tgl_jatuh_tempo'     => $tglJatuhTempo,
            'pokok_pinjaman_awal' => $request->pokok_pinjaman_awal,
        ]);

        return redirect()
            ->route('data.index')
            ->with('success', 'Data berhasil diperbarui');
    }

    /* =========================
     * DELETE
     * ========================= */
    public function destroy(string $id)
    {
        Peminjaman::findOrFail($id)->delete();
        return redirect()->route('data.index')->with('hapus', 'Data berhasil dihapus');
    }
}
