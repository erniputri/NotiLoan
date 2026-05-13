<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class NotificationScheduleService
{
    // Setiap pinjaman aktif punya row notifikasi terpisah per periode bulanan agar histori reminder tetap terbaca.
    public function syncForLoan(
        Peminjaman $peminjaman,
        ?Carbon $periodStart = null,
        bool $createIfMissing = false
    ): ?Notification {
        return DB::transaction(function () use ($peminjaman, $periodStart, $createIfMissing) {
            $loan = $peminjaman->loadMissing(['latestPembayaran', 'notifikasi']);

            if ((int) $loan->pokok_sisa <= 0) {
                return $loan->notifikasi;
            }

            $periodStart = $this->resolvePeriodStart($periodStart);
            $notification = Notification::query()
                ->where('peminjaman_id', $loan->id)
                ->whereDate('period_start', $periodStart->toDateString())
                ->lockForUpdate()
                ->latest('id')
                ->first();

            if (! $notification && ! $createIfMissing) {
                return $loan->notifikasi;
            }

            $nextDueDate = $loan->next_due_date;
            $sendAt = $this->resolveMonthlyBatchDate($periodStart);
            $payload = $this->buildPayload($loan, $nextDueDate, $sendAt, $periodStart);

            if (! $notification) {
                return $loan->notifications()->create($payload + [
                    'status' => false,
                    'sent_at' => null,
                    'follow_up_sent_at' => null,
                ]);
            }

            // Reminder yang sudah terkirim disimpan sebagai histori apa adanya.
            if ($notification->sent_at) {
                return $notification->refresh();
            }

            $notification->update($payload);

            return $notification->refresh();
        });
    }

    // Batch tanggal 1 kini menyiapkan reminder bulanan untuk seluruh pinjaman yang belum lunas.
    public function prepareMonthlyNotifications(?Carbon $referenceDate = null): Collection
    {
        $periodStart = $this->resolvePeriodStart($referenceDate);

        return Peminjaman::query()
            ->where('pokok_sisa', '>', 0)
            ->with(['latestPembayaran', 'notifikasi'])
            ->get()
            ->map(function (Peminjaman $loan) use ($periodStart) {
                return $this->syncForLoan($loan, $periodStart, true);
            })
            ->filter()
            ->values();
    }

    public function firstRemindersReadyForDispatch(?Carbon $referenceDate = null): Collection
    {
        $referenceDate = ($referenceDate ?: now())->copy();
        $periodStart = $this->resolvePeriodStart($referenceDate);

        return Notification::query()
            ->with(['peminjaman.latestPembayaran'])
            ->whereDate('period_start', $periodStart->toDateString())
            ->where('status', false)
            ->where('send_at', '<=', $referenceDate)
            ->whereHas('peminjaman', function ($query) {
                $query->where('pokok_sisa', '>', 0);
            })
            ->latest('peminjaman_id')
            ->get();
    }

    // Reminder kedua bekerja per periode bulanan aktif, lalu berhenti ketika periode berikutnya sudah dibuat.
    public function secondRemindersReadyForDispatch(?Carbon $referenceDate = null): Collection
    {
        # tanggal acuan diubah ke awal hari, lalu dicari periode bulan aktif
        $referenceDate = ($referenceDate ?: now())->copy()->startOfDay();
        $periodStart = $this->resolvePeriodStart($referenceDate);

        #query mengambil notifikasi yang memenuhi syarat dasar
        return Notification::query()
            ->with(['peminjaman.latestPembayaran'])
            ->whereDate('period_start', $periodStart->toDateString())
            ->where('status', true) #artinya notifikasi pertama sudah pernah terkirim
            ->whereDate('due_date', '<=', $referenceDate->toDateString())
            ->whereNull('follow_up_sent_at')
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

    private function buildPayload(
        Peminjaman $peminjaman,
        Carbon $nextDueDate,
        Carbon $sendAt,
        Carbon $periodStart
    ): array {
        return [
            'kontak' => $peminjaman->kontak,
            'message' => $this->buildMonthlyMessage($peminjaman, $nextDueDate, $periodStart),
            'due_date' => $nextDueDate->toDateString(),
            'period_start' => $periodStart->toDateString(),
            'send_at' => $sendAt,
        ];
    }

    public function buildMonthlyMessage(Peminjaman $peminjaman, Carbon $nextDueDate, ?Carbon $periodStart = null): string
    {
        $virtualAccount = $peminjaman->formatted_virtual_account ?: 'belum tersedia';
        $periodStart = $this->resolvePeriodStart($periodStart);

        if ($nextDueDate->lt($periodStart)) {
            return sprintf(
                'Yth %s, hingga awal bulan %s pembayaran pinjaman Anda yang jatuh tempo pada %s belum kami terima. Mohon segera melakukan pembayaran melalui Virtual Account %s.',
                $peminjaman->nama_mitra,
                $periodStart->translatedFormat('F Y'),
                $nextDueDate->format('Y-m-d'),
                $virtualAccount
            );
        }

        return sprintf(
            'Yth %s, pembayaran pinjaman Anda dijadwalkan jatuh tempo pada %s. Silakan siapkan pembayaran melalui Virtual Account %s.',
            $peminjaman->nama_mitra,
            $nextDueDate->format('Y-m-d'),
            $virtualAccount
        );
    }

    public function buildOverdueMessage(Peminjaman $peminjaman, Carbon $nextDueDate): string
    {
        $virtualAccount = $peminjaman->formatted_virtual_account ?: 'belum tersedia';

        return sprintf(
            'Yth %s, pembayaran pinjaman Anda telah jatuh tempo pada %s dan belum kami terima. Mohon segera melakukan pembayaran melalui Virtual Account %s.',
            $peminjaman->nama_mitra,
            $nextDueDate->format('Y-m-d'),
            $virtualAccount
        );
    }

    public function hasSentSecondReminderForCurrentDueDate(Notification $notification): bool
    {
        return (bool) $notification->follow_up_sent_at;
    }

    private function resolveMonthlyBatchDate(?Carbon $periodStart = null): Carbon
    {
        return $this->resolvePeriodStart($periodStart)->setTime(0, 5);
    }

    private function resolvePeriodStart(?Carbon $referenceDate = null): Carbon
    {
        return ($referenceDate ?: now())->copy()->startOfMonth();
    }
}
