<?php

namespace App\Imports;

use App\Models\Peminjaman;
use App\Services\MitraService;
use App\Services\NotificationScheduleService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PeminjamanImport implements ToCollection, WithHeadingRow
{
    public function __construct(
        private readonly ?MitraService $mitraService = null
    ) {
    }

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

        foreach ($rows as $index => $row) {
            $row = collect($row)->toArray();

            // Skip jika seluruh baris praktis kosong agar import tidak berhenti di baris sisa template.
            if (! $this->hasMeaningfulValue($row)) {
                continue;
            }

            $this->validateRow($row, $index);

            DB::transaction(function () use ($row, $index) {
                $mitraPayload = [
                    'nomor_mitra' => $this->requiredString($row, 'nomor_mitra', $index),
                    'virtual_account_bank' => $this->requiredString($row, 'virtual_account_bank', $index),
                    'virtual_account' => $this->requiredString($row, 'virtual_account', $index),
                    'nama_mitra' => $this->requiredString($row, 'nama_mitra', $index),
                    'kontak' => $this->requiredString($row, 'kontak', $index),
                    'alamat' => $this->requiredString($row, 'alamat', $index),
                    'kabupaten' => $this->requiredString($row, 'kabupaten', $index),
                    'sektor' => $this->requiredString($row, 'sektor', $index),
                ];

                $mitraService = $this->mitraService ?? app(MitraService::class);
                $mitra = $mitraService->resolveOrCreate($mitraPayload);
                $mitraService->guardActiveLoanConflict($mitra);

                $pokokPinjamanAwal = (int) $this->normalizeNumber($this->requiredValue($row, 'pokok_pinjaman_awal', $index));
                $lamaAngsuran = (int) $this->normalizeNumber($this->requiredValue($row, 'lama_angsuran_bulan', $index));
                $bungaPersen = (float) $this->normalizeNumber($this->requiredValue($row, 'bunga_persen', $index));
                $administrasiAwal = (int) $this->normalizeNumber($this->requiredValue($row, 'administrasi_awal', $index));

                $peminjaman = Peminjaman::create([
                    'mitra_id' => $mitra->id,
                    'nomor_mitra' => $mitraPayload['nomor_mitra'],
                    'virtual_account_bank' => $mitraPayload['virtual_account_bank'],
                    'virtual_account' => $mitraPayload['virtual_account'],
                    'nama_mitra' => $mitraPayload['nama_mitra'],
                    'kontak' => $mitraPayload['kontak'],
                    'alamat' => $mitraPayload['alamat'],
                    'kabupaten' => $mitraPayload['kabupaten'],
                    'sektor' => $mitraPayload['sektor'],
                    'tgl_peminjaman' => $this->parseDateValue($this->requiredValue($row, 'tgl_peminjaman', $index), 'tgl_peminjaman', $index),
                    'tgl_jatuh_tempo' => $this->parseDateValue($this->requiredValue($row, 'tgl_jatuh_tempo', $index), 'tgl_jatuh_tempo', $index),
                    'tgl_akhir_pinjaman' => $this->parseDateValue($this->requiredValue($row, 'tgl_akhir_pinjaman', $index), 'tgl_akhir_pinjaman', $index),
                    'lama_angsuran_bulan' => $lamaAngsuran,
                    'bunga_persen' => $bungaPersen,
                    'pokok_pinjaman_awal' => $pokokPinjamanAwal,
                    'administrasi_awal' => $administrasiAwal,
                    'no_surat_perjanjian' => $this->requiredString($row, 'no_surat_perjanjian', $index),
                    'jaminan' => $this->requiredString($row, 'jaminan', $index),
                    'pokok_cicilan_sd' => 0,
                    'jasa_cicilan_sd' => 0,
                    'pokok_sisa' => $pokokPinjamanAwal,
                    'jasa_sisa' => 0,
                    'kualitas_kredit' => 'Lancar',
                ]);

                $peminjaman->syncKualitasKredit();
                app(NotificationScheduleService::class)->syncForLoan($peminjaman);
            });
        }
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
                $this->parseDateValue($value, $key, $index);
            }

            return $value;
        }

        throw ValidationException::withMessages([
            'file' => 'Kolom ' . $key . ' wajib ada pada template dan wajib diisi pada baris ' . ($index + 2) . '.',
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

    protected function parseDateValue(mixed $value, string $key, int $index): Carbon
    {
        try {
            if (is_numeric($value)) {
                return Carbon::instance(ExcelDate::excelToDateTimeObject((float) $value))->startOfDay();
            }

            return Carbon::parse(trim((string) $value))->startOfDay();
        } catch (\Throwable) {
            throw ValidationException::withMessages([
                'file' => 'Kolom ' . $key . ' pada baris ' . ($index + 2) . ' harus berupa tanggal yang valid.',
            ]);
        }
    }
}
