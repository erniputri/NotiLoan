<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;

class NotificationScheduleService
{
    // Satu pinjaman dijaga agar selalu punya jadwal notifikasi yang selaras dengan jatuh temponya.
    public function syncForLoan(Peminjaman $peminjaman): Notification
    {
        // Reminder default dikirim 3 hari sebelum jatuh tempo, lalu dimundurkan ke "sekarang" jika sudah lewat.
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

        // Jika notifikasi belum ada sistem akan membuat baru, jika sudah ada cukup diperbarui.
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
