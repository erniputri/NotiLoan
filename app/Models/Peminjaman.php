<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'nomor_mitra',
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

    protected $casts = [
        'tgl_peminjaman' => 'date',
        'tgl_jatuh_tempo' => 'date',
        'tgl_akhir_pinjaman' => 'date',
        'bunga_persen' => 'decimal:2',
        'pokok_pinjaman_awal' => 'integer',
        'administrasi_awal' => 'integer',
        'pokok_cicilan_sd' => 'integer',
        'jasa_cicilan_sd' => 'integer',
        'pokok_sisa' => 'integer',
        'jasa_sisa' => 'integer',
    ];

    public function notifikasi()
    {
        return $this->hasOne(Notification::class, 'peminjaman_id', 'id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function latestPembayaran()
    {
        return $this->hasOne(Pembayaran::class)->ofMany('tanggal_pembayaran', 'max');
    }

    public function resolveNextDueDate(): Carbon
    {
        $referenceDate = $this->relationLoaded('latestPembayaran')
            ? $this->latestPembayaran?->tanggal_pembayaran
            : $this->pembayaran()->latest('tanggal_pembayaran')->value('tanggal_pembayaran');

        $referenceDate ??= $this->tgl_peminjaman;

        return Carbon::parse($referenceDate)->addMonth()->startOfDay();
    }

    public function getKualitasKreditAttribute($value)
    {
        return $value ?: $this->hitungKualitasKredit();
    }

    public function syncKualitasKredit(bool $persist = true): string
    {
        $kualitasKredit = $this->hitungKualitasKredit();

        $this->attributes['kualitas_kredit'] = $kualitasKredit;

        if ($persist && $this->exists) {
            $this->saveQuietly();
        }

        return $kualitasKredit;
    }

    public function hitungKualitasKredit(): string
    {
        if ((int) $this->pokok_sisa === 0) {
            return 'Lancar';
        }

        $pembayaranTerakhir = $this->relationLoaded('latestPembayaran')
            ? $this->latestPembayaran
            : $this->pembayaran()->latest('tanggal_pembayaran')->first();

        $tanggalAcuan = $pembayaranTerakhir?->tanggal_pembayaran ?? $this->tgl_peminjaman;
        $selisihHari = Carbon::parse($tanggalAcuan)->diffInDays(now());

        if ($selisihHari <= 30) {
            return 'Lancar';
        }

        if ($selisihHari <= 90) {
            return 'Kurang Lancar';
        }

        if ($selisihHari <= 270) {
            return 'Ragu-ragu';
        }

        return 'Macet';
    }

    public function getFormattedTglPeminjamanAttribute(): ?string
    {
        return $this->tgl_peminjaman?->format('Y-m-d');
    }

    public function getFormattedTglJatuhTempoAttribute(): ?string
    {
        return $this->tgl_jatuh_tempo?->format('Y-m-d');
    }

    public function getNextDueDateAttribute(): Carbon
    {
        return $this->resolveNextDueDate();
    }

    public function getFormattedNextDueDateAttribute(): string
    {
        return $this->resolveNextDueDate()->format('Y-m-d');
    }

    public function getFormattedPokokPinjamanAwalAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->pokok_pinjaman_awal, 0, ',', '.');
    }

    public function getFormattedPokokSisaAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->pokok_sisa, 0, ',', '.');
    }

    public function getFormattedAdministrasiAwalAttribute(): string
    {
        return 'Rp ' . number_format((int) $this->administrasi_awal, 0, ',', '.');
    }

    public function getFormattedLamaAngsuranBulanAttribute(): string
    {
        return number_format((int) $this->lama_angsuran_bulan, 0, ',', '.') . ' bulan';
    }

    public function getFormattedBungaPersenAttribute(): string
    {
        return rtrim(rtrim(number_format((float) $this->bunga_persen, 2, ',', '.'), '0'), ',') . '%';
    }

    public function getLoanStatusLabelAttribute(): string
    {
        return (int) $this->pokok_sisa === 0 ? 'Lunas' : 'Aktif';
    }

    public function getLoanStatusClassAttribute(): string
    {
        return (int) $this->pokok_sisa === 0 ? 'success' : 'warning';
    }

    public function getKualitasKreditClassAttribute(): string
    {
        return match ($this->kualitas_kredit) {
            'Lancar' => 'success',
            'Kurang Lancar' => 'warning',
            'Ragu-ragu' => 'info',
            'Macet' => 'danger',
            default => 'secondary',
        };
    }

    public function getKualitasKreditLabelAttribute(): string
    {
        return $this->kualitas_kredit ?: 'Tidak Diketahui';
    }

    public function getNotificationStatusLabelAttribute(): string
    {
        if ((int) $this->pokok_sisa === 0) {
            return 'Lunas';
        }

        if (! $this->notifikasi) {
            return $this->is_due_and_unpaid ? 'Menunggu' : 'Belum Jatuh Tempo';
        }

        return $this->notifikasi->status ? 'Terkirim' : 'Menunggu';
    }

    public function getNotificationStatusClassAttribute(): string
    {
        if ((int) $this->pokok_sisa === 0) {
            return 'secondary';
        }

        if (! $this->notifikasi) {
            return $this->is_due_and_unpaid ? 'warning' : 'secondary';
        }

        return $this->notifikasi->status ? 'success' : 'warning';
    }

    public function getIsDueAndUnpaidAttribute(): bool
    {
        return (int) $this->pokok_sisa > 0
            && $this->resolveNextDueDate()->lte(now()->startOfDay());
    }

    public function getShouldShowNotificationSendActionAttribute(): bool
    {
        return $this->is_due_and_unpaid
            && (! $this->notifikasi || ! $this->notifikasi->status);
    }
}
