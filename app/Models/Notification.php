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
        'sent_at',
        'status',
    ];

    protected $casts = [
        'send_at' => 'datetime',
        'sent_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id');
    }

    public function attempts()
    {
        return $this->hasMany(NotificationAttempt::class);
    }
}
