<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    // Daftar ini menentukan kolom angka mana yang layak dijumlahkan otomatis di baris total Excel.
    private const SUMMABLE_COLUMNS = [
        'pokok_pinjaman_awal',
        'administrasi_awal',
        'pokok_cicilan_sd',
        'jasa_cicilan_sd',
        'pokok_sisa',
        'jasa_sisa',
    ];

    private const CURRENCY_COLUMNS = [
        'pokok_pinjaman_awal',
        'administrasi_awal',
        'pokok_cicilan_sd',
        'jasa_cicilan_sd',
        'pokok_sisa',
        'jasa_sisa',
    ];

    private const DATE_COLUMNS = [
        'tgl_peminjaman',
        'tgl_jatuh_tempo',
        'tgl_akhir_pinjaman',
        'created_at',
        'updated_at',
    ];

    private array $selectedColumns;

    // Constructor menerima pilihan kolom dari admin lalu membersihkannya agar hanya kolom yang diizinkan yang lolos.
    public function __construct(
        array $selectedColumns = [],
        private readonly ?string $search = null
    ) {
        $allowedColumns = array_keys(self::availableColumns());

        $this->selectedColumns = array_values(array_intersect($selectedColumns, $allowedColumns));

        if ($this->selectedColumns === []) {
            $this->selectedColumns = self::defaultColumns();
        }
    }

    // Method ini menjadi sumber tunggal daftar kolom yang boleh dipakai oleh form export dan file hasil export.
    public static function availableColumns(): array
    {
        return [
            'id' => 'ID',
            'nomor_mitra' => 'Nomor Mitra',
            'virtual_account' => 'Virtual Account',
            'nama_mitra' => 'Nama Mitra',
            'kontak' => 'Kontak',
            'alamat' => 'Alamat',
            'kabupaten' => 'Kabupaten',
            'sektor' => 'Sektor',
            'tgl_peminjaman' => 'Tanggal Peminjaman',
            'tgl_jatuh_tempo' => 'Tanggal Jatuh Tempo',
            'tgl_akhir_pinjaman' => 'Tanggal Akhir Pinjaman',
            'lama_angsuran_bulan' => 'Lama Angsuran (Bulan)',
            'bunga_persen' => 'Bunga (%)',
            'pokok_pinjaman_awal' => 'Pokok Pinjaman Awal',
            'administrasi_awal' => 'Administrasi Awal',
            'pokok_cicilan_sd' => 'Pokok Cicilan s/d',
            'jasa_cicilan_sd' => 'Jasa Cicilan s/d',
            'pokok_sisa' => 'Pokok Sisa',
            'jasa_sisa' => 'Jasa Sisa',
            'kualitas_kredit' => 'Kualitas Kredit',
            'no_surat_perjanjian' => 'No. Surat Perjanjian',
            'jaminan' => 'Jaminan',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    // Kolom default dipakai ketika admin belum memilih kombinasi kolom sendiri.
    public static function defaultColumns(): array
    {
        return [
            'nomor_mitra',
            'nama_mitra',
            'kontak',
            'kabupaten',
            'tgl_peminjaman',
            'tgl_jatuh_tempo',
            'pokok_pinjaman_awal',
            'pokok_sisa',
            'kualitas_kredit',
        ];
    }

    // Collection mengambil data sesuai filter pencarian yang aktif di halaman data.
    public function collection()
    {
        return $this->buildQuery()
            ->orderBy('id')
            ->get();
    }

    // Heading mengikuti urutan kolom yang dipilih admin agar isi file konsisten dengan checklist UI.
    public function headings(): array
    {
        return array_map(
            fn (string $column) => self::availableColumns()[$column],
            $this->selectedColumns
        );
    }

    // Mapping dibuat dinamis supaya satu class export bisa melayani banyak kombinasi kolom.
    public function map($row): array
    {
        return array_map(
            fn (string $column) => $this->resolveColumnValue($row, $column),
            $this->selectedColumns
        );
    }

    // Event setelah sheet selesai dipakai untuk mempercantik hasil Excel tanpa mengganggu query utama.
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '2E7D32'],
                    ],
                ]);

                $sheet->setAutoFilter("A1:{$lastColumn}{$lastRow}");
                $sheet->freezePane('A2');

                if ($lastRow < 2) {
                    return;
                }

                // Format angka dan tanggal diputuskan berdasarkan kolom yang benar-benar ikut diexport.
                foreach ($this->selectedColumns as $index => $column) {
                    $columnLetter = Coordinate::stringFromColumnIndex($index + 1);

                    if (in_array($column, self::CURRENCY_COLUMNS, true)) {
                        $sheet->getStyle("{$columnLetter}2:{$columnLetter}{$lastRow}")
                            ->getNumberFormat()
                            ->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
                    }

                    if (in_array($column, self::DATE_COLUMNS, true)) {
                        $sheet->getStyle("{$columnLetter}2:{$columnLetter}{$lastRow}")
                            ->getNumberFormat()
                            ->setFormatCode('yyyy-mm-dd');
                    }
                }

                $statusIndex = array_search('kualitas_kredit', $this->selectedColumns, true);

                // Kolom kualitas kredit diberi warna supaya status cepat dikenali saat file dibuka.
                if ($statusIndex !== false) {
                    $statusColumn = Coordinate::stringFromColumnIndex($statusIndex + 1);

                    for ($row = 2; $row <= $lastRow; $row++) {
                        $status = $sheet->getCell("{$statusColumn}{$row}")->getValue();

                        $color = match ($status) {
                            'Lancar' => 'C8E6C9',
                            'Kurang Lancar' => 'FFF3CD',
                            'Ragu-ragu' => 'D1ECF1',
                            'Macet' => 'F8D7DA',
                            default => 'E9ECEF',
                        };

                        $sheet->getStyle("{$statusColumn}{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => $color],
                            ],
                        ]);
                    }
                }

                $sumColumns = array_values(array_filter(
                    $this->selectedColumns,
                    fn (string $column) => in_array($column, self::SUMMABLE_COLUMNS, true)
                ));

                if ($sumColumns === []) {
                    return;
                }

                // Baris total hanya ditambahkan jika memang ada kolom angka yang relevan untuk dijumlahkan.
                $totalRow = $lastRow + 1;
                $sheet->setCellValue('A'.$totalRow, 'TOTAL');

                foreach ($sumColumns as $column) {
                    $columnIndex = array_search($column, $this->selectedColumns, true);
                    $columnLetter = Coordinate::stringFromColumnIndex($columnIndex + 1);

                    $sheet->setCellValue(
                        "{$columnLetter}{$totalRow}",
                        "=SUM({$columnLetter}2:{$columnLetter}{$lastRow})"
                    );
                }

                $sheet->getStyle("A{$totalRow}:{$lastColumn}{$totalRow}")
                    ->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8F5E9'],
                        ],
                    ]);
            },
        ];
    }

    // Query builder dipisah agar aturan filter export konsisten dengan halaman data.
    private function buildQuery(): Builder
    {
        return Peminjaman::query()
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $innerQuery) {
                    $innerQuery->where('nama_mitra', 'like', '%'.$this->search.'%')
                        ->orWhere('kontak', 'like', '%'.$this->search.'%')
                        ->orWhere('kabupaten', 'like', '%'.$this->search.'%')
                        ->orWhere('sektor', 'like', '%'.$this->search.'%');
                });
            });
    }

    // Helper ini merapikan nilai sebelum masuk ke Excel, terutama untuk kolom tanggal dan timestamp.
    private function resolveColumnValue(Peminjaman $row, string $column): mixed
    {
        return match ($column) {
            'tgl_peminjaman',
            'tgl_jatuh_tempo',
            'tgl_akhir_pinjaman' => optional($row->{$column})->format('Y-m-d'),
            'created_at',
            'updated_at' => optional($row->{$column})->format('Y-m-d H:i:s'),
            default => $row->{$column},
        };
    }
}
