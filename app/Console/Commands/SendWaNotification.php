<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWaNotification extends Command
{
    protected $signature = 'wa:send-notification';
    protected $description = 'Kirim notifikasi WhatsApp otomatis';

    public function handle()
    {
        $notifications = Notification::where('status', 0)
            ->where('send_at', '<=', now())
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('Tidak ada notifikasi yang perlu dikirim.');
            return;
        }

        foreach ($notifications as $notif) {

            // SIMULASI KIRIM WA
            Log::info('SIMULASI WA TERKIRIM', [
                'ke'      => $notif->kontak,
                'message' => $notif->message,
            ]);

            $this->info("WA terkirim ke {$notif->kontak}");

            $notif->update([
                'status'  => 1,
                'sent_at' => now(),
            ]);
        }

        $this->info('Semua notifikasi berhasil diproses.');
    }
}
