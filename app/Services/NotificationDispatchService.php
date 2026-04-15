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
        return DB::transaction(function () use ($notification, $triggerType) {
            $notification->loadMissing('peminjaman');

            $attempt = $notification->attempts()->create([
                'peminjaman_id' => $notification->peminjaman_id,
                'kontak' => $notification->kontak,
                'message' => $notification->message,
                'channel' => 'whatsapp',
                'trigger_type' => $triggerType,
                'send_status' => 'processing',
                'payload' => [
                    'kontak' => $notification->kontak,
                    'message' => $notification->message,
                ],
                'attempted_at' => now(),
            ]);

            // Pengiriman masih disimulasikan, tetapi hasil hit tetap dicatat ke tabel agar bisa diverifikasi.
            Log::info('SIMULASI WA TERKIRIM', [
                'trigger' => $triggerType,
                'ke' => $notification->kontak,
                'message' => $notification->message,
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
                'sent_at' => now(),
            ]);

            return $attempt->refresh();
        });
    }
}
