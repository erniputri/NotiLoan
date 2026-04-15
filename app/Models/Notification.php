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
        'due_date',
        'send_at',
        'sent_at',
        'follow_up_sent_at',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'send_at' => 'datetime',
        'sent_at' => 'datetime',
        'follow_up_sent_at' => 'datetime',
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
