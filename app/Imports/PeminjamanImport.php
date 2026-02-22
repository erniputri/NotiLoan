<?php

namespace App\Imports;

use App\Models\Peminjaman;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class PeminjamanImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            // Skip jika kosong
            if (!$row['nama_mitra']) {
                continue;
            }

            $tglPinjam = Carbon::parse($row['tanggal_peminjaman']);
            $lama = (int) $row['lama_angsuran_bulan'];

            Peminjaman::create([

                'nomor_mitra'         => $row['nomor_mitra'] ?? null,
                'nama_mitra'          => $row['nama_mitra'],
                'kontak'              => $row['kontak'],
                'alamat'              => $row['alamat'],
                'kabupaten'           => $row['kabupaten'],
                'sektor'              => $row['sektor'],

                'pokok_pinjaman_awal' => $row['pokok_pinjaman'],
                'administrasi_awal'   => $row['administrasi_awal'],
                'bunga_persen'        => $row['bunga'] ?? 0,

                'tgl_peminjaman'      => $tglPinjam,
                'tgl_jatuh_tempo'     => $tglPinjam->copy()->addMonths($lama),
                'tgl_akhir_pinjaman'  => $tglPinjam->copy()->addMonths($lama),

                'lama_angsuran_bulan' => $lama,

                'pokok_sisa'       => $row['pokok_pinjaman'],
                'jasa_sisa'        => 0,
                'pokok_cicilan_sd' => 0,
                'jasa_cicilan_sd'  => 0,
                'kualitas_kredit'  => 'Lancar',
            ]);

        }
    }
}
