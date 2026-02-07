<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Peminjaman::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nomor Mitra',
            'Nama Mitra',
            'Kontak',
            'Alamat',
            'Kabupaten',
            'Sektor',
            'Pokok Pinjaman',
            'Administrasi Awal',
            'Bunga (%)',
            'Tanggal Peminjaman',
            'Tanggal Jatuh Tempo',
            'Lama Angsuran (bulan)',
            'Kualitas Kredit',
            'Created At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->nomor_mitra,
            $row->nama_mitra,
            $row->kontak,
            $row->alamat,
            $row->kabupaten,
            $row->sektor,
            $row->pokok_pinjaman_awal,
            $row->administrasi_awal,
            $row->bunga_persen,
            $row->tgl_peminjaman,
            $row->tgl_jatuh_tempo,
            $row->lama_angsuran_bulan,
            $row->kualitas_kredit,
            $row->created_at,
        ];
    }
}
