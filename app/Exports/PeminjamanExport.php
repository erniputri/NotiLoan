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
            'Nama',
            'Kontak',
            'Tanggal Peminjaman',
            'Tanggal Pengembalian',
            'Jumlah',
            'Created At',
            'Updated At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->peminjaman_id,
            $row->nama,
            $row->kontak,
            $row->tgl_peminjaman,
            $row->tgl_pengembalian,
            $row->jumlah,
            $row->created_at,
            $row->updated_at,
        ];
    }
}
