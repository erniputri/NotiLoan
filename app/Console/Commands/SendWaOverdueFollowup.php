<?php

namespace App\Console\Commands;

use App\Services\NotificationDispatchService;
use App\Services\NotificationScheduleService;
use Illuminate\Console\Command;

class SendWaOverdueFollowup extends Command
{
    protected $signature = 'wa:send-overdue-followup';
    protected $description = 'Kirim notifikasi kedua untuk mitra yang sudah jatuh tempo dan belum membayar';

    public function __construct(
        private readonly NotificationScheduleService $notificationScheduleService,
        private readonly NotificationDispatchService $notificationDispatchService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $notifications = $this->notificationScheduleService->secondRemindersReadyForDispatch(now());

        if ($notifications->isEmpty()) {
            $this->info('Tidak ada notifikasi kedua yang perlu dikirim.');
            return self::SUCCESS;
        }

        foreach ($notifications as $notification) {
            $attempt = $this->notificationDispatchService->dispatchSecondReminder($notification, 'second_notice_system');
            $this->info("WA pengingat kedua diproses ke {$notification->kontak} (attempt #{$attempt->id}).");
        }

        $this->info('Semua notifikasi kedua berhasil diproses.');

        return self::SUCCESS;
    }
}
