<?php

namespace App\Console\Commands;

use App\Services\NotificationDispatchService;
use App\Services\NotificationScheduleService;
use Illuminate\Console\Command;

class SendWaNotification extends Command
{
    protected $signature = 'wa:send-notification';
    protected $description = 'Kirim notifikasi WhatsApp otomatis pada batch awal bulan';

    public function __construct(
        private readonly NotificationScheduleService $notificationScheduleService,
        private readonly NotificationDispatchService $notificationDispatchService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $referenceDate = now();
        $preparedNotifications = $this->notificationScheduleService->prepareMonthlyNotifications($referenceDate);
        $notifications = $this->notificationScheduleService->notificationsReadyForDispatch($referenceDate);

        if ($notifications->isEmpty()) {
            $this->info('Tidak ada notifikasi jatuh tempo yang perlu dikirim.');
            return self::SUCCESS;
        }

        foreach ($notifications as $notif) {
            $attempt = $this->notificationDispatchService->dispatch($notif, 'system');
            $this->info("WA batch awal bulan diproses ke {$notif->kontak} (attempt #{$attempt->id}).");
        }

        $this->info("Semua notifikasi berhasil diproses. Queue bulan ini disiapkan: {$preparedNotifications->count()}.");

        return self::SUCCESS;
    }
}
