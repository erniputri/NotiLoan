<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PeminjamanExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithEvents
{
    /**
     * AMBIL SEMUA DATA TANPA KECUALI
     */
    public function collection()
    {
        return Peminjaman::orderBy('id')->get();
    }

    /**
     * HEADER EXCEL
     */
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
            'Lama Angsuran (Bulan)',
            'Kualitas Kredit',
            'Created At',
        ];
    }

    /**
     * ISI BARIS
     */
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

    /**
     * EVENT SETELAH SHEET JADI
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                /**
                 * HEADER STYLE
                 */
                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2F5597'],
                    ],
                ]);

                /**
                 * AUTO FILTER & FREEZE
                 */
                $sheet->setAutoFilter("A1:{$lastColumn}{$lastRow}");
                $sheet->freezePane('A2');

                /**
                 * FORMAT RUPIAH
                 */
                $sheet->getStyle("H2:I{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

                /**
                 * FORMAT TANGGAL
                 */
                $sheet->getStyle("K2:L{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('yyyy-mm-dd');

                /**
                 * WARNA BERDASARKAN KUALITAS KREDIT
                 */
                for ($row = 2; $row <= $lastRow; $row++) {
                    $status = $sheet->getCell("N{$row}")->getValue();

                    if ($status === 'Lancar') {
                        $color = 'C6EFCE';
                    } elseif ($status === 'Kurang Lancar') {
                        $color = 'FFEB9C';
                    } else {
                        $color = 'FFC7CE'; // Macet
                    }

                    $sheet->getStyle("N{$row}")->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $color],
                        ],
                    ]);
                }

                /**
                 * BARIS TOTAL
                 */
                $totalRow = $lastRow + 1;

                $sheet->setCellValue("G{$totalRow}", 'TOTAL');
                $sheet->setCellValue("H{$totalRow}", "=SUM(H2:H{$lastRow})");
                $sheet->setCellValue("I{$totalRow}", "=SUM(I2:I{$lastRow})");

                $sheet->getStyle("G{$totalRow}:I{$totalRow}")->applyFromArray([
                    'font' => ['bold' => true],
                ]);
            },
        ];
    }
}
