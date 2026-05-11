<?php

namespace App\Models\Concerns;

trait FormatsIndonesianContact
{
    public function setKontakAttribute($value): void
    {
        $this->attributes['kontak'] = $this->normalizeKontak($value);
    }

    public function getKontakAttribute($value): ?string
    {
        return $this->normalizeKontak($value);
    }

    protected function normalizeKontak(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        if (! is_string($digits) || $digits === '') {
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
            return '+62';
        }

        return '+62 ' . $localNumber;
    }
}
