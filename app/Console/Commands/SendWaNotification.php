<?php

namespace App\Console\Commands;

use App\Services\NotificationAutomationService;
use Illuminate\Console\Command;

class SendWaNotification extends Command
{
    protected $signature = 'wa:send-notification';
    protected $description = 'Kirim notifikasi WhatsApp otomatis pada batch awal bulan';

    public function __construct(
        private readonly NotificationAutomationService $notificationAutomationService
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $result = $this->notificationAutomationService->dispatchMonthlyBatch(now());

        if ($result['processed_count'] === 0) {
            $this->info('Tidak ada notifikasi bulanan yang perlu dikirim.');
            return self::SUCCESS;
        }

        $this->info("Semua notifikasi berhasil diproses. Queue bulan ini disiapkan: {$result['prepared_count']}.");
        $this->line('Attempt ID: #' . implode(', #', $result['attempt_ids']));

        return self::SUCCESS;
    }
}
