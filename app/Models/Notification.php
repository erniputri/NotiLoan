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

    public function setKontakAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['kontak'] = null;

            return;
        }

        $value = trim((string) $value);

        if ($value === '') {
            $this->attributes['kontak'] = null;

            return;
        }

        $digits = preg_replace('/\D+/', '', $value);

        if ($digits === '') {
            $this->attributes['kontak'] = $value;

            return;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        if (! str_starts_with($digits, '62')) {
            $this->attributes['kontak'] = $value;

            return;
        }

        $localNumber = ltrim(substr($digits, 2), '0');

        $this->attributes['kontak'] = $localNumber === ''
            ? '(+62)'
            : '(+62) ' . $localNumber;
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'peminjaman_id', 'id');
    }

    public function attempts()
    {
        return $this->hasMany(NotificationAttempt::class);
    }
}
