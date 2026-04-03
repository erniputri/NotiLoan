<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PeminjamanTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [];
    }

    public function headings(): array
    {
        return [
            'nomor_mitra',
            'nama_mitra',
            'kontak',
            'alamat',
            'kabupaten',
            'sektor',
            'pokok_pinjaman',
            'administrasi_awal',
            'bunga',
            'tanggal_peminjaman',
            'lama_angsuran_bulan',
        ];
    }
}
