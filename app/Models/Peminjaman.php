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
}
