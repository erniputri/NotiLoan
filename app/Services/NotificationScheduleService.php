<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;

class NotificationScheduleService
{
    public function syncForLoan(Peminjaman $peminjaman): Notification
    {
        $sendAt = Carbon::parse($peminjaman->tgl_jatuh_tempo)->subDays(3);

        if ($sendAt->isPast()) {
            $sendAt = now();
        }

        $notification = $peminjaman->notifikasi()->first();

        $payload = [
            'kontak' => $peminjaman->kontak,
            'message' => sprintf(
                'Yth %s, pinjaman Anda akan jatuh tempo pada %s.',
                $peminjaman->nama_mitra,
                Carbon::parse($peminjaman->tgl_jatuh_tempo)->format('Y-m-d')
            ),
            'send_at' => $sendAt,
        ];

        if (! $notification) {
            return $peminjaman->notifikasi()->create($payload + [
                'status' => 0,
                'sent_at' => null,
            ]);
        }

        if ((int) $notification->status === 0) {
            $payload['sent_at'] = null;
        }

        $notification->update($payload);

        return $notification->refresh();
    }
}
