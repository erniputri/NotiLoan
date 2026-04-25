<?php

namespace App\Services;

use Carbon\Carbon;

class NotificationAutomationService
{
    public function __construct(
        private readonly NotificationScheduleService $notificationScheduleService,
        private readonly NotificationDispatchService $notificationDispatchService
    ) {
    }

    // Batch pertama tetap mengikuti aturan awal bulan, hanya saja bisa dipicu manual untuk kebutuhan demo.
    public function dispatchMonthlyBatch(?Carbon $referenceDate = null): array
    {
        $referenceDate ??= now();

        $preparedNotifications = $this->notificationScheduleService->prepareMonthlyNotifications($referenceDate);
        $notifications = $this->notificationScheduleService->firstRemindersReadyForDispatch($referenceDate);

        $attemptIds = [];

        foreach ($notifications as $notification) {
            $attempt = $this->notificationDispatchService->dispatch($notification, 'first_notice_system');
            $attemptIds[] = $attempt->id;
        }

        return [
            'prepared_count' => $preparedNotifications->count(),
            'processed_count' => count($attemptIds),
            'attempt_ids' => $attemptIds,
        ];
    }

    // Batch kedua hanya mengirim ke mitra yang sudah jatuh tempo dan belum melakukan pembayaran.
    public function dispatchOverdueFollowUpBatch(?Carbon $referenceDate = null): array
    {
        $referenceDate ??= now();

        $notifications = $this->notificationScheduleService->secondRemindersReadyForDispatch($referenceDate);
        $attemptIds = [];

        foreach ($notifications as $notification) {
            $attempt = $this->notificationDispatchService->dispatchSecondReminder($notification, 'second_notice_system');
            $attemptIds[] = $attempt->id;
        }

        return [
            'processed_count' => count($attemptIds),
            'attempt_ids' => $attemptIds,
        ];
    }
}
