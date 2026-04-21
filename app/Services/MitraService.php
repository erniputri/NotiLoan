<?php

namespace App\Services;

use App\Models\Mitra;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MitraService
{
    public function resolveOrCreate(array $data, ?Peminjaman $peminjaman = null): Mitra
    {
        $payload = $this->normalizePayload($data);
        $mitra = $this->findExistingMitra($data, $payload, $peminjaman);

        if ($mitra) {
            $mitra->fill($payload);
            $mitra->save();

            return $mitra;
        }

        return Mitra::create($payload);
    }

    public function syncLoanWithMitra(Peminjaman $peminjaman, array $data): Peminjaman
    {
        $mitra = $this->resolveOrCreate($data, $peminjaman);
        $payload = $this->normalizePayload($data);
        $this->guardActiveLoanConflict($mitra, $peminjaman);

        $peminjaman->mitra()->associate($mitra);
        $peminjaman->fill($payload);
        $peminjaman->save();

        return $peminjaman->refresh();
    }

    public function guardActiveLoanConflict(Mitra $mitra, ?Peminjaman $peminjaman = null): void
    {
        $currentLoanId = $peminjaman?->id;
        $currentLoanIsSettled = $peminjaman && (int) $peminjaman->pokok_sisa === 0;

        if ($currentLoanIsSettled) {
            return;
        }

        if ($mitra->relationLoaded('peminjaman')) {
            $hasOtherActiveLoan = $mitra->peminjaman
                ->filter(function (Peminjaman $loan) use ($currentLoanId) {
                    if ($currentLoanId && $loan->id === $currentLoanId) {
                        return false;
                    }

                    return (int) $loan->pokok_sisa > 0;
                })
                ->isNotEmpty();
        } else {
            $hasOtherActiveLoan = $mitra->peminjaman()
                ->where('pokok_sisa', '>', 0)
                ->when($currentLoanId, fn ($query) => $query->where('id', '!=', $currentLoanId))
                ->exists();
        }

        if (! $hasOtherActiveLoan) {
            return;
        }

        throw ValidationException::withMessages([
            'nama_mitra' => 'Mitra ini masih memiliki pinjaman aktif. Pinjaman baru hanya boleh ditambahkan setelah pinjaman sebelumnya lunas.',
        ]);
    }

    public function updateMitra(Mitra $mitra, array $data): Mitra
    {
        return DB::transaction(function () use ($mitra, $data) {
            $payload = $this->normalizePayload($data);
            $mitra->fill($payload);
            $mitra->save();

            $mitra->peminjaman()->update($payload);

            return $mitra->refresh();
        });
    }

    private function findExistingMitra(array $data, array $payload, ?Peminjaman $peminjaman = null): ?Mitra
    {
        if ($peminjaman?->mitra_id) {
            return Mitra::query()->find($peminjaman->mitra_id);
        }

        if (! empty($data['mitra_id'])) {
            return Mitra::query()->find($data['mitra_id']);
        }

        if (! empty($payload['nomor_mitra'])) {
            return Mitra::query()
                ->where('nomor_mitra', $payload['nomor_mitra'])
                ->where('nama_mitra', $payload['nama_mitra'])
                ->first();
        }

        return Mitra::query()
            ->where('nama_mitra', $payload['nama_mitra'])
            ->where('kontak', $payload['kontak'])
            ->first();
    }

    private function normalizePayload(array $data): array
    {
        return [
            'nomor_mitra' => $data['nomor_mitra'] ?? null,
            'virtual_account_bank' => $data['virtual_account_bank'] ?? null,
            'virtual_account' => $data['virtual_account'] ?? null,
            'nama_mitra' => $data['nama_mitra'],
            'kontak' => $data['kontak'],
            'alamat' => $data['alamat'] ?? null,
            'kabupaten' => $data['kabupaten'] ?? null,
            'sektor' => $data['sektor'] ?? null,
        ];
    }
}
