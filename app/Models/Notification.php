<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
    'peminjaman_id',
    'kontak',
    'message',
    'send_at',
    'status'
];

public function peminjaman()
{
    return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'peminjaman_id');
}
}
