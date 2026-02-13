<?php
namespace App\Models;

use App\Models\Pembayaran;
use App\Models\Notification;
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

    public function notifikasi()
    {
        return $this->hasOne(Notification::class, 'peminjaman_id', 'id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
