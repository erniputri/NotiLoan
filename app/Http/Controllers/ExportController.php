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

            fputcsv($handle, [
                'ID',
                'Nomor Mitra',
                'Nama Mitra',
                'Kontak',
                'Tanggal Peminjaman',
                'Tanggal Jatuh Tempo',
                'Pokok Pinjaman',
                'Sisa Pokok',
                'Kualitas Kredit',
                'Created At',
                'Updated At'
            ]);

            Peminjaman::orderBy('id')->cursor()->each(function ($row) use ($handle) {
                fputcsv($handle, [
                    $row->id,
                    $row->nomor_mitra,
                    $row->nama_mitra,
                    $row->kontak,
                    $row->tgl_peminjaman,
                    $row->tgl_jatuh_tempo,
                    $row->pokok_pinjaman_awal,
                    $row->pokok_sisa,
                    $row->kualitas_kredit,
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
