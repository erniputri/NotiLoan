<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function export()
    {
        $fileName = 'data-peminjaman.csv';

        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // HEADER CSV
            fputcsv($handle, [
                'ID',
                'Nama',
                'Kontak',
                'Tanggal Peminjaman',
                'Tanggal Pengembalian',
                'Jumlah',
                'Created At',
                'Updated At'
            ]);

            // DATA
            Peminjaman::all()->each(function ($row) use ($handle) {
                fputcsv($handle, [
                    $row->peminjaman_id,
                    $row->nama,
                    $row->kontak,
                    $row->tgl_peminjaman,
                    $row->tgl_pengembalian,
                    $row->jumlah,
                    $row->created_at,
                    $row->updated_at,
                ]);
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="'.$fileName.'"'
        );

        return $response;
    }
}
