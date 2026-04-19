<?php

namespace App\Imports;

use App\Models\Peminjaman;
use App\Services\NotificationScheduleService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PeminjamanImport implements ToCollection, WithHeadingRow
{
    private const IMPORT_COLUMNS = [
        'nomor_mitra',
        'virtual_account_bank',
        'virtual_account',
        'nama_mitra',
        'kontak',
        'alamat',
        'kabupaten',
        'sektor',
        'tgl_peminjaman',
        'tgl_jatuh_tempo',
        'tgl_akhir_pinjaman',
        'lama_angsuran_bulan',
        'bunga_persen',
        'pokok_pinjaman_awal',
        'administrasi_awal',
        'no_surat_perjanjian',
        'jaminan',
        'pokok_cicilan_sd',
        'jasa_cicilan_sd',
        'pokok_sisa',
        'jasa_sisa',
        'kualitas_kredit',
    ];

    public static function importColumns(): array
    {
        return self::IMPORT_COLUMNS;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw ValidationException::withMessages([
                'file' => 'File import tidak berisi data. Gunakan template import yang sudah disediakan sistem.',
            ]);
        }

        $this->validateHeaders(collect($rows->first())->toArray());

        foreach ($rows as $index => $row) {
            $row = collect($row)->toArray();

            // Skip jika seluruh baris praktis kosong agar import tidak berhenti di baris sisa template.
            if (! $this->hasMeaningfulValue($row)) {
                continue;
            }

            $this->validateRow($row, $index);

            $peminjaman = Peminjaman::create([
                'nomor_mitra' => $this->requiredString($row, 'nomor_mitra', $index),
                'virtual_account_bank' => $this->requiredString($row, 'virtual_account_bank', $index),
                'virtual_account' => $this->requiredString($row, 'virtual_account', $index),
                'nama_mitra' => $this->requiredString($row, 'nama_mitra', $index),
                'kontak' => $this->requiredString($row, 'kontak', $index),
                'alamat' => $this->requiredString($row, 'alamat', $index),
                'kabupaten' => $this->requiredString($row, 'kabupaten', $index),
                'sektor' => $this->requiredString($row, 'sektor', $index),
                'tgl_peminjaman' => Carbon::parse($this->requiredString($row, 'tgl_peminjaman', $index)),
                'tgl_jatuh_tempo' => Carbon::parse($this->requiredString($row, 'tgl_jatuh_tempo', $index)),
                'tgl_akhir_pinjaman' => Carbon::parse($this->requiredString($row, 'tgl_akhir_pinjaman', $index)),
                'lama_angsuran_bulan' => (int) $this->normalizeNumber($this->requiredValue($row, 'lama_angsuran_bulan', $index)),
                'bunga_persen' => (float) $this->normalizeNumber($this->requiredValue($row, 'bunga_persen', $index)),
                'pokok_pinjaman_awal' => (int) $this->normalizeNumber($this->requiredValue($row, 'pokok_pinjaman_awal', $index)),
                'administrasi_awal' => (int) $this->normalizeNumber($this->requiredValue($row, 'administrasi_awal', $index)),
                'no_surat_perjanjian' => $this->requiredString($row, 'no_surat_perjanjian', $index),
                'jaminan' => $this->requiredString($row, 'jaminan', $index),
                'pokok_cicilan_sd' => (int) $this->normalizeNumber($this->requiredValue($row, 'pokok_cicilan_sd', $index)),
                'jasa_cicilan_sd' => (int) $this->normalizeNumber($this->requiredValue($row, 'jasa_cicilan_sd', $index)),
                'pokok_sisa' => (int) $this->normalizeNumber($this->requiredValue($row, 'pokok_sisa', $index)),
                'jasa_sisa' => (int) $this->normalizeNumber($this->requiredValue($row, 'jasa_sisa', $index)),
                'kualitas_kredit' => $this->requiredString($row, 'kualitas_kredit', $index),
            ]);

            app(NotificationScheduleService::class)->syncForLoan($peminjaman);
        }
    }

    private function validateHeaders(array $row): void
    {
        $headers = array_keys($row);
        $missingHeaders = array_values(array_diff(self::IMPORT_COLUMNS, $headers));

        if ($missingHeaders === []) {
            return;
        }

        throw ValidationException::withMessages([
            'file' => 'Header file import belum lengkap. Kolom yang wajib ada: ' . implode(', ', $missingHeaders) . '.',
        ]);
    }

    private function validateRow(array $row, int $index): void
    {
        foreach (self::IMPORT_COLUMNS as $column) {
            $this->requiredValue($row, $column, $index);
        }
    }

    private function requiredValue(array $row, string $key, int $index): mixed
    {
        if (array_key_exists($key, $row) && $row[$key] !== null && trim((string) $row[$key]) !== '') {
            $value = $row[$key];

            if (in_array($key, ['tgl_peminjaman', 'tgl_jatuh_tempo', 'tgl_akhir_pinjaman'], true)) {
                Carbon::parse($value);
            }

            return $value;
        }

        throw ValidationException::withMessages([
            'file' => 'Kolom ' . $key . ' wajib diisi pada baris ' . ($index + 2) . '.',
        ]);
    }

    private function requiredString(array $row, string $key, int $index): string
    {
        return trim((string) $this->requiredValue($row, $key, $index));
    }

    private function hasMeaningfulValue(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return true;
            }
        }

        return false;
    }

    private function normalizeNumber(mixed $value): float|int
    {
        if (is_int($value) || is_float($value)) {
            return $value;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return 0;
        }

        $value = str_replace(['Rp', 'rp', ' '], '', $value);

        if (str_contains($value, ',') && str_contains($value, '.')) {
            $value = str_replace('.', '', $value);
            $value = str_replace(',', '.', $value);
        } elseif (str_contains($value, ',')) {
            $value = str_replace(',', '.', $value);
        }

        return is_numeric($value) ? $value + 0 : 0;
    }
}
