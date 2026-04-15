<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationAttempt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationDispatchService
{
    // Semua hit pengiriman ditampung ke tabel attempt agar proses testing dan audit bisa dibaca dari database.
    public function dispatch(Notification $notification, string $triggerType = 'system'): NotificationAttempt
    {
        return $this->dispatchMessage($notification, $notification->message, $triggerType, false);
    }

    public function dispatchSecondReminder(Notification $notification, string $triggerType = 'second_notice_system'): NotificationAttempt
    {
        $notification->loadMissing('peminjaman.latestPembayaran');

        $message = app(NotificationScheduleService::class)->buildOverdueMessage(
            $notification->peminjaman,
            $notification->peminjaman->next_due_date
        );

        return $this->dispatchMessage($notification, $message, $triggerType, true);
    }

    private function dispatchMessage(
        Notification $notification,
        string $message,
        string $triggerType,
        bool $isFollowUp
    ): NotificationAttempt
    {
        return DB::transaction(function () use ($notification, $message, $triggerType, $isFollowUp) {
            $notification->loadMissing('peminjaman');

            if ($isFollowUp && $notification->follow_up_sent_at) {
                return $notification->attempts()->create([
                    'peminjaman_id' => $notification->peminjaman_id,
                    'kontak' => $notification->kontak,
                    'message' => $message,
                    'channel' => 'whatsapp',
                    'trigger_type' => $triggerType,
                    'send_status' => 'skipped',
                    'payload' => [
                        'kontak' => $notification->kontak,
                        'message' => $message,
                    ],
                    'response_code' => 'FOLLOW_UP_ALREADY_SENT',
                    'response_body' => 'Notifikasi kedua untuk siklus jatuh tempo ini sudah pernah dikirim.',
                    'is_success' => false,
                    'attempted_at' => now(),
                ]);
            }

            $attempt = $notification->attempts()->create([
                'peminjaman_id' => $notification->peminjaman_id,
                'kontak' => $notification->kontak,
                'message' => $message,
                'channel' => 'whatsapp',
                'trigger_type' => $triggerType,
                'send_status' => 'processing',
                'payload' => [
                    'kontak' => $notification->kontak,
                    'message' => $message,
                ],
                'attempted_at' => now(),
            ]);

            // Pengiriman masih disimulasikan, tetapi hasil hit tetap dicatat ke tabel agar bisa diverifikasi.
            Log::info('SIMULASI WA TERKIRIM', [
                'trigger' => $triggerType,
                'ke' => $notification->kontak,
                'message' => $message,
                'notification_id' => $notification->id,
                'attempt_id' => $attempt->id,
            ]);

            $attempt->update([
                'send_status' => 'success',
                'response_code' => 'SIMULATED',
                'response_body' => 'Pesan berhasil diproses oleh simulator WhatsApp.',
                'is_success' => true,
            ]);

            $notification->update([
                'status' => true,
                'sent_at' => $isFollowUp ? $notification->sent_at : now(),
                'follow_up_sent_at' => $isFollowUp ? now() : $notification->follow_up_sent_at,
            ]);

            return $attempt->refresh();
        });
    }
}
