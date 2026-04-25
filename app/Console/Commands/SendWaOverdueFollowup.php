<?php

namespace App\Console\Commands;

use App\Services\NotificationAutomationService;
use Illuminate\Console\Command;

class SendWaOverdueFollowup extends Command
{
    protected $signature = 'wa:send-overdue-followup';
    protected $description = 'Kirim notifikasi kedua untuk mitra yang sudah jatuh tempo dan belum membayar';

    public function __construct(
        private readonly NotificationAutomationService $notificationAutomationService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $result = $this->notificationAutomationService->dispatchOverdueFollowUpBatch(now());

        if ($result['processed_count'] === 0) {
            $this->info('Tidak ada notifikasi kedua yang perlu dikirim.');
            return self::SUCCESS;
        }

        $this->info('Semua notifikasi kedua berhasil diproses.');
        $this->line('Attempt ID: #' . implode(', #', $result['attempt_ids']));

        return self::SUCCESS;
    }
}
