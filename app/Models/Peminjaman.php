<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'peminjaman_id';
    protected $fillable = [
        'nama',
        'kontak',
        'tgl_peminjaman',
        'tgl_pengembalian',
        'jumlah'
    ];
    public function notifikasi()
{
    return $this->hasOne(Notification::class, 'peminjaman_id', 'peminjaman_id');
}
}
