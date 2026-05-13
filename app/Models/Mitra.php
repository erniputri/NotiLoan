<?php

namespace App\Models;

use App\Models\Concerns\FormatsIndonesianContact;
use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    use FormatsIndonesianContact;

    protected $fillable = [
        'nomor_mitra',
        'virtual_account_bank',
        'virtual_account',
        'nama_mitra',
        'kontak',
        'alamat',
        'kabupaten',
        'sektor',
    ];

    public function setNomorMitraAttribute($value): void
    {
        $this->attributes['nomor_mitra'] = $value === null ? null : trim((string) $value);
    }

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function latestPeminjaman()
    {
        return $this->hasOne(Peminjaman::class)->latestOfMany('tgl_peminjaman');
    }

    public function getFormattedVirtualAccountAttribute(): ?string
    {
        $bank = $this->virtual_account_bank ? trim((string) $this->virtual_account_bank) : null;
        $number = $this->virtual_account ? trim((string) $this->virtual_account) : null;

        if (! $bank && ! $number) {
            return null;
        }

        if ($bank && $number) {
            return $bank . ' - ' . $number;
        }

        return $bank ?: $number;
    }

    public function getActiveLoanCountAttribute(): int
    {
        if (array_key_exists('active_loan_count', $this->attributes)) {
            return (int) $this->attributes['active_loan_count'];
        }

        if ($this->relationLoaded('peminjaman')) {
            return $this->peminjaman->where('pokok_sisa', '>', 0)->count();
        }

        return $this->peminjaman()->where('pokok_sisa', '>', 0)->count();
    }

    public function getSettledLoanCountAttribute(): int
    {
        if ($this->relationLoaded('peminjaman')) {
            return $this->peminjaman->where('pokok_sisa', 0)->count();
        }

        return $this->peminjaman()->where('pokok_sisa', 0)->count();
    }

    public function getTotalPinjamanAttribute(): int
    {
        if (array_key_exists('total_pinjaman', $this->attributes)) {
            return (int) $this->attributes['total_pinjaman'];
        }

        if ($this->relationLoaded('peminjaman')) {
            return (int) $this->peminjaman->sum('pokok_pinjaman_awal');
        }

        return (int) $this->peminjaman()->sum('pokok_pinjaman_awal');
    }

    public function getTotalSisaAttribute(): int
    {
        if (array_key_exists('total_sisa', $this->attributes)) {
            return (int) $this->attributes['total_sisa'];
        }

        if ($this->relationLoaded('peminjaman')) {
            return (int) $this->peminjaman->sum('pokok_sisa');
        }

        return (int) $this->peminjaman()->sum('pokok_sisa');
    }

    public function getFormattedTotalPinjamanAttribute(): string
    {
        return 'Rp ' . number_format($this->total_pinjaman, 0, ',', '.');
    }

    public function getFormattedTotalSisaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_sisa, 0, ',', '.');
    }

}
