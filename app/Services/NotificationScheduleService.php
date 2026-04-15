<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class NotificationScheduleService
{
    // Satu pinjaman dijaga agar selalu punya jadwal notifikasi yang selaras dengan siklus jatuh tempo bulanan.
    public function syncForLoan(Peminjaman $peminjaman): ?Notification
    {
        $loan = $peminjaman->loadMissing(['latestPembayaran', 'notifikasi']);

        if ((int) $loan->pokok_sisa <= 0) {
            return $loan->notifikasi;
        }

        $nextDueDate = $loan->next_due_date;
        $sendAt = $this->resolveFirstBatchDate($nextDueDate);
        $notification = $loan->notifikasi;
        $payload = $this->buildPayload($loan, $nextDueDate, $sendAt);

        if (! $notification) {
            return $loan->notifikasi()->create($payload + [
                'status' => 0,
                'sent_at' => null,
            ]);
        }

        $sameSchedule = $notification->send_at
            && $notification->send_at->equalTo($sendAt);

        if (! $sameSchedule) {
            $payload['status'] = 0;
            $payload['sent_at'] = null;
        }

        $notification->update($payload);

        return $notification->refresh();
    }

    // Setiap awal bulan sistem menyiapkan kembali notifikasi untuk mitra yang sudah jatuh tempo namun belum membayar.
    public function prepareMonthlyNotifications(?Carbon $referenceDate = null): Collection
    {
        $referenceDate = ($referenceDate ?: now())->copy()->startOfMonth();

        return Peminjaman::query()
            ->where('pokok_sisa', '>', 0)
            ->with(['latestPembayaran', 'notifikasi'])
            ->get()
            ->filter(fn (Peminjaman $loan) => $this->isLoanDueAndUnpaid($loan, $referenceDate))
            ->map(function (Peminjaman $loan) use ($referenceDate) {
                $notification = $loan->notifikasi;
                $payload = $this->buildPayload($loan, $loan->next_due_date, $referenceDate);

                if (! $notification) {
                    return $loan->notifikasi()->create($payload + [
                        'status' => 0,
                        'sent_at' => null,
                    ]);
                }

                if ($notification->sent_at && $notification->sent_at->isSameMonth($referenceDate)) {
                    return $notification;
                }

                $notification->update($payload + [
                    'status' => 0,
                    'sent_at' => null,
                ]);

                return $notification->refresh();
            })
            ->values();
    }

    public function notificationsReadyForDispatch(?Carbon $referenceDate = null): Collection
    {
        $referenceDate = ($referenceDate ?: now())->copy();

        return Notification::query()
            ->with(['peminjaman.latestPembayaran'])
            ->where('status', false)
            ->where('send_at', '<=', $referenceDate)
            ->whereHas('peminjaman', function ($query) {
                $query->where('pokok_sisa', '>', 0);
            })
            ->get()
            ->filter(fn (Notification $notification) => $notification->peminjaman
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
            'message' => $this->buildMessage($peminjaman, $nextDueDate),
            'send_at' => $sendAt,
        ];
    }

    private function buildMessage(Peminjaman $peminjaman, Carbon $nextDueDate): string
    {
        $virtualAccount = $peminjaman->formatted_virtual_account ?: 'belum tersedia';

        return sprintf(
            'Yth %s, pembayaran pinjaman Anda yang jatuh tempo pada %s belum kami terima. Silakan lakukan pembayaran melalui Virtual Account %s.',
            $peminjaman->nama_mitra,
            $nextDueDate->format('Y-m-d'),
            $virtualAccount
        );
    }

    private function resolveFirstBatchDate(Carbon $nextDueDate): Carbon
    {
        $firstDayOfMonth = $nextDueDate->copy()->startOfMonth();

        return $nextDueDate->isSameDay($firstDayOfMonth)
            ? $firstDayOfMonth
            : $firstDayOfMonth->addMonth();
    }
}
