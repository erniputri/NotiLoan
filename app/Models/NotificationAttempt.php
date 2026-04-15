<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationAttempt extends Model
{
    protected $fillable = [
        'notification_id',
        'peminjaman_id',
        'kontak',
        'message',
        'channel',
        'trigger_type',
        'send_status',
        'payload',
        'response_code',
        'response_body',
        'is_success',
        'attempted_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'is_success' => 'boolean',
        'attempted_at' => 'datetime',
    ];

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}
