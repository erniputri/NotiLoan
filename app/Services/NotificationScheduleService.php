<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationScheduleService
{
    // Satu pinjaman dijaga agar selalu punya jadwal notifikasi untuk siklus jatuh tempo aktifnya.
    public function syncForLoan(Peminjaman $peminjaman): ?Notification
    {
        $loan = $peminjaman->loadMissing(['latestPembayaran', 'notifikasi']);

        if ((int) $loan->pokok_sisa <= 0) {
            return $loan->notifikasi;
        }

        $nextDueDate = $loan->next_due_date;
        $sendAt = $this->resolveMonthlyBatchDate($nextDueDate);
        $notification = $loan->notifikasi;
        $payload = $this->buildPayload($loan, $nextDueDate, $sendAt);

        if (! $notification) {
            return $loan->notifikasi()->create($payload + [
                'status' => 0,
                'sent_at' => null,
                'follow_up_sent_at' => null,
            ]);
        }

        $sameCycle = $notification->due_date
            && $notification->due_date->isSameDay($nextDueDate);

        if (! $sameCycle) {
            $payload['status'] = 0;
            $payload['sent_at'] = null;
            $payload['follow_up_sent_at'] = null;
        }

        $notification->update($payload);

        return $notification->refresh();
    }

    // Batch tanggal 1 hanya menyiapkan reminder pertama untuk pinjaman yang jatuh tempo di bulan berjalan.
    public function prepareMonthlyNotifications(?Carbon $referenceDate = null): Collection
    {
        $referenceDate = ($referenceDate ?: now())->copy()->startOfMonth();
        $monthStart = $referenceDate->copy()->startOfMonth();
        $monthEnd = $referenceDate->copy()->endOfMonth();

        return Peminjaman::query()
            ->where('pokok_sisa', '>', 0)
            ->with(['latestPembayaran', 'notifikasi'])
            ->get()
            ->filter(fn (Peminjaman $loan) => $loan->next_due_date->between($monthStart, $monthEnd))
            ->map(function (Peminjaman $loan) use ($referenceDate) {
                return $this->syncForLoan($loan);
            })
            ->filter()
            ->values();
    }

    public function firstRemindersReadyForDispatch(?Carbon $referenceDate = null): Collection
    {
        $referenceDate = ($referenceDate ?: now())->copy();
        $monthStart = $referenceDate->copy()->startOfMonth();
        $monthEnd = $referenceDate->copy()->endOfMonth();

        return Notification::query()
            ->with(['peminjaman.latestPembayaran'])
            ->where('status', false)
            ->where('send_at', '<=', $referenceDate)
            ->whereBetween('due_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->whereHas('peminjaman', function ($query) {
                $query->where('pokok_sisa', '>', 0);
            })
            ->get()
            ->filter(fn (Notification $notification) => $notification->peminjaman
                && $notification->due_date
                && $notification->due_date->isSameDay($notification->peminjaman->next_due_date))
            ->values();
    }

    // Reminder kedua hanya dipilih jika tanggal jatuh tempo sudah masuk dan pembayaran belum terjadi.
    public function secondRemindersReadyForDispatch(?Carbon $referenceDate = null): Collection
    {
        $referenceDate = ($referenceDate ?: now())->copy()->startOfDay();

        return Notification::query()
            ->with(['peminjaman.latestPembayaran'])
            ->whereDate('due_date', '<=', $referenceDate->toDateString())
            ->whereNull('follow_up_sent_at')
            ->whereHas('peminjaman', function ($query) {
                $query->where('pokok_sisa', '>', 0);
            })
            ->get()
            ->filter(fn (Notification $notification) => $notification->peminjaman
                && $notification->due_date
                && $notification->due_date->isSameDay($notification->peminjaman->next_due_date)
                && $this->isLoanDueAndUnpaid($notification->peminjaman, $referenceDate))
            ->values();
    }

    public function isLoanDueAndUnpaid(Peminjaman $peminjaman, ?Carbon $referenceDate = null): bool
    {
        $referenceDate = ($referenceDate ?: now())->copy()->startOfDay();
        $loan = $peminjaman->loadMissing('latestPembayaran');

        return (int) $loan->pokok_sisa > 0
            && $loan->next_due_date->lte($referenceDate);
    }

    private function buildPayload(Peminjaman $peminjaman, Carbon $nextDueDate, Carbon $sendAt): array
    {
        return [
            'kontak' => $peminjaman->kontak,
            'message' => $this->buildMonthlyMessage($peminjaman, $nextDueDate),
            'due_date' => $nextDueDate->toDateString(),
            'send_at' => $sendAt,
        ];
    }

    public function buildMonthlyMessage(Peminjaman $peminjaman, Carbon $nextDueDate): string
    {
        $virtualAccount = $peminjaman->virtual_account ?: 'belum tersedia';

        return sprintf(
            'Yth %s, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada %s. Silakan siapkan pembayaran melalui Virtual Account %s.',
            $peminjaman->nama_mitra,
            $nextDueDate->format('Y-m-d'),
            $virtualAccount
        );
    }

    public function buildOverdueMessage(Peminjaman $peminjaman, Carbon $nextDueDate): string
    {
        $virtualAccount = $peminjaman->virtual_account ?: 'belum tersedia';

        return sprintf(
            'Yth %s, pembayaran pinjaman Anda telah jatuh tempo pada %s dan belum kami terima. Mohon segera melakukan pembayaran melalui Virtual Account %s.',
            $peminjaman->nama_mitra,
            $nextDueDate->format('Y-m-d'),
            $virtualAccount
        );
    }

    public function hasSentSecondReminderForCurrentDueDate(Notification $notification): bool
    {
        $notification->loadMissing('peminjaman.latestPembayaran');

        return (bool) (
            $notification->follow_up_sent_at
            && $notification->due_date
            && $notification->peminjaman
            && $notification->due_date->isSameDay($notification->peminjaman->next_due_date)
        );
    }

    private function resolveMonthlyBatchDate(Carbon $nextDueDate): Carbon
    {
        return $nextDueDate->copy()->startOfMonth()->setTime(0, 5);
    }
}
