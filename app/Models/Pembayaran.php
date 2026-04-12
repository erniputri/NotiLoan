<?php

namespace App\Models;

use App\Models\Peminjaman;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'peminjaman_id',
        'tanggal_pembayaran',
        'jumlah_bayar',
        'bukti_pembayaran'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'jumlah_bayar' => 'integer',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function getFormattedTanggalPembayaranAttribute(): ?string
    {
        return $this->tanggal_pembayaran?->format('Y-m-d');
    }

    public function getFormattedJumlahBayarAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->jumlah_bayar, 0, ',', '.');
    }

    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        return $this->bukti_pembayaran
            ? asset('storage/' . $this->bukti_pembayaran)
            : null;
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        if (! $this->relationLoaded('peminjaman') && ! $this->peminjaman) {
            return 'Belum Bayar';
        }

        if ((int) $this->peminjaman->pokok_sisa === 0) {
            return 'Lunas';
        }

        if ((int) $this->peminjaman->pokok_sisa < (int) $this->peminjaman->pokok_pinjaman_awal) {
            return 'Mencicil';
        }

        return 'Belum Bayar';
    }

    public function getPaymentStatusClassAttribute(): string
    {
        return match ($this->payment_status_label) {
            'Lunas' => 'success',
            'Mencicil' => 'warning',
            default => 'danger',
        };
    }
}
