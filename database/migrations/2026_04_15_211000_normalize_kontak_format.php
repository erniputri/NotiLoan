<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeTable('peminjaman');
        $this->normalizeTable('notifications');
    }

    public function down(): void
    {
        $this->restoreTable('peminjaman');
        $this->restoreTable('notifications');
    }

    private function normalizeTable(string $table): void
    {
        DB::table($table)
            ->select(['id', 'kontak'])
            ->whereNotNull('kontak')
            ->orderBy('id')
            ->get()
            ->each(function ($row) use ($table) {
                $formatted = $this->normalizeKontak($row->kontak);

                if ($formatted !== $row->kontak) {
                    DB::table($table)
                        ->where('id', $row->id)
                        ->update(['kontak' => $formatted]);
                }
            });
    }

    private function restoreTable(string $table): void
    {
        DB::table($table)
            ->select(['id', 'kontak'])
            ->whereNotNull('kontak')
            ->where('kontak', 'like', '(+62)%')
            ->orderBy('id')
            ->get()
            ->each(function ($row) use ($table) {
                $digits = preg_replace('/\D+/', '', (string) $row->kontak);

                if (! str_starts_with($digits, '62')) {
                    return;
                }

                $localNumber = ltrim(substr($digits, 2), '0');

                DB::table($table)
                    ->where('id', $row->id)
                    ->update(['kontak' => $localNumber === '' ? null : '0' . $localNumber]);
            });
    }

    private function normalizeKontak(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        if ($digits === '') {
            return $value;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        if (! str_starts_with($digits, '62')) {
            return $value;
        }

        $localNumber = ltrim(substr($digits, 2), '0');

        if ($localNumber === '') {
            return '(+62)';
        }

        return '(+62) ' . $localNumber;
    }
};
