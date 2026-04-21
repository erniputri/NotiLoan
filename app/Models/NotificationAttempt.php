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

    public function getFormattedAttemptedAtAttribute(): ?string
    {
        return $this->attempted_at?->format('Y-m-d H:i');
    }

    public function getTriggerTypeLabelAttribute(): string
    {
        return match ($this->trigger_type) {
            'system' => 'Otomatis',
            'second_notice_system' => 'Pengingat Kedua Otomatis',
            'second_notice_manual' => 'Pengingat Kedua Manual',
            default => ucwords(str_replace('_', ' ', (string) $this->trigger_type)),
        };
    }

    public function getSendStatusLabelAttribute(): string
    {
        return match ($this->send_status) {
            'success' => 'Berhasil',
            'processing' => 'Diproses',
            'skipped' => 'Dilewati',
            'failed' => 'Gagal',
            default => ucfirst((string) $this->send_status),
        };
    }

    public function getSendStatusClassAttribute(): string
    {
        return match ($this->send_status) {
            'success' => 'success',
            'processing' => 'warning',
            'skipped' => 'secondary',
            'failed' => 'danger',
            default => 'info',
        };
    }
}
