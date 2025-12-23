<?php
namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendWaNotification extends Command
{
    protected $signature   = 'wa:send-notification';
    protected $description = 'Kirim notifikasi WhatsApp otomatis';

    public function handle()
    {
        $notifications = Notification::where('status', 0)
            ->where('send_at', '<=', now())
            ->get();

        foreach ($notifications as $notif) {
            Log::info('Kirim WA ke: ' . $notif->kontak);

            $notif->update([
                'status'  => 1,
                'send_at' => now(),
            ]);
        }
    }
}
