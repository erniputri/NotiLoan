<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardService
{
    private const PRIORITY_LIMIT = 3;
    private const RECENT_PAYMENT_LIMIT = 4;
    private const UPCOMING_LIMIT = 6;
    private const OVERDUE_LIMIT = 6;

    public function build(): array
    {
        $today = now()->startOfDay();
        $thirtyDaysAhead = now()->copy()->addDays(30)->endOfDay();
        $sevenDaysAhead = now()->copy()->addDays(7)->endOfDay();

        $loanStats = [
            'total' => Peminjaman::count(),
            'active' => Peminjaman::where('pokok_sisa', '>', 0)->count(),
            'settled' => Peminjaman::where('pokok_sisa', 0)->count(),
        ];

        $notificationStats = [
            'total' => Notification::count(),
            'pending' => Notification::where('status', false)->count(),
            'sent' => Notification::where('status', true)->count(),
        ];

        $qualityBreakdown = Peminjaman::query()
            ->selectRaw('COALESCE(kualitas_kredit, ?) as kualitas, COUNT(*) as total', ['Tidak Diketahui'])
            ->groupBy('kualitas')
            ->pluck('total', 'kualitas');

        $activeLoans = Peminjaman::query()
            ->select([
                'id',
                'nama_mitra',
                'kontak',
                'tgl_peminjaman',
                'tgl_jatuh_tempo',
                'pokok_pinjaman_awal',
                'pokok_sisa',
                'lama_angsuran_bulan',
                'kualitas_kredit',
            ])
            ->where('pokok_sisa', '>', 0)
            ->with([
                'latestPembayaran' => function ($query) {
                    $query->select([
                        'pembayarans.id',
                        'pembayarans.peminjaman_id',
                        'pembayarans.tanggal_pembayaran',
                    ]);
                },
                'notifikasi' => function ($query) {
                    $query->select([
                        'notifications.id',
                        'notifications.peminjaman_id',
                        'notifications.status',
                        'notifications.send_at',
                        'notifications.sent_at',
                    ]);
                },
            ])
            ->get();

        $dueItems = $this->transformDueItems($activeLoans, $today);

        $upcomingItems = $dueItems
            ->filter(fn (array $item) => $item['next_due_date']->between($today, $thirtyDaysAhead))
            ->sortBy('next_due_date')
            ->values();

        $overdueItems = $dueItems
            ->filter(fn (array $item) => $item['next_due_date']->lt($today))
            ->sortBy('next_due_date')
            ->values();

        $priorityItems = $overdueItems
            ->concat($upcomingItems->take(4))
            ->unique('id')
            ->sortBy('next_due_date')
            ->values();

        $recentPayments = Pembayaran::query()
            ->select(['id', 'peminjaman_id', 'tanggal_pembayaran', 'jumlah_bayar', 'created_at'])
            ->with('peminjaman:id,nama_mitra')
            ->latest('tanggal_pembayaran')
            ->paginate(
                self::RECENT_PAYMENT_LIMIT,
                ['*'],
                'recent_page'
            )
            ->withQueryString();

        return [
            'loanStats' => $loanStats,
            'notificationStats' => $notificationStats,
            'chartData' => $qualityBreakdown,
            'priorityItems' => $this->paginateCollection(
                $priorityItems,
                self::PRIORITY_LIMIT,
                'priority_page'
            ),
            'upcomingItems' => $upcomingItems->take(self::UPCOMING_LIMIT),
            'overdueItems' => $overdueItems->take(self::OVERDUE_LIMIT),
            'dueStats' => [
                'today' => $dueItems->where('status_key', 'today')->count(),
                'upcoming_7_days' => $dueItems->filter(
                    fn (array $item) => $item['next_due_date']->between($today, $sevenDaysAhead)
                )->count(),
                'upcoming_30_days' => $upcomingItems->count(),
                'overdue' => $overdueItems->count(),
            ],
            'recentPayments' => $recentPayments,
        ];
    }

    private function paginateCollection(Collection $items, int $perPage, string $pageName): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage($pageName);
        $currentItems = $items
            ->forPage($currentPage, $perPage)
            ->values();

        return new LengthAwarePaginator(
            $currentItems,
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => $pageName,
                'query' => request()->query(),
            ]
        );
    }

    private function transformDueItems(Collection $activeLoans, Carbon $today): Collection
    {
        return $activeLoans->map(function (Peminjaman $peminjaman) use ($today) {
            $referenceDate = $peminjaman->latestPembayaran?->tanggal_pembayaran ?? $peminjaman->tgl_peminjaman;
            $nextDueDate = Carbon::parse($referenceDate)->addMonth()->startOfDay();
            $daysRemaining = $today->diffInDays($nextDueDate, false);

            return [
                'id' => $peminjaman->id,
                'nama_mitra' => $peminjaman->nama_mitra,
                'kontak' => $peminjaman->kontak,
                'pokok_sisa' => (int) $peminjaman->pokok_sisa,
                'kualitas_kredit' => $peminjaman->kualitas_kredit,
                'next_due_date' => $nextDueDate,
                'days_remaining' => $daysRemaining,
                'notification_status' => $this->resolveNotificationStatus($peminjaman->notifikasi),
                'status_key' => $this->resolveStatusKey($daysRemaining),
                'status_label' => $this->resolveStatusLabel($daysRemaining),
                'status_badge' => $this->resolveStatusBadge($daysRemaining),
            ];
        });
    }

    private function resolveNotificationStatus($notification): string
    {
        if (! $notification) {
            return 'Belum Dijadwalkan';
        }

        if ($notification->status) {
            return 'Terkirim';
        }

        return 'Menunggu';
    }

    private function resolveStatusKey(int $daysRemaining): string
    {
        if ($daysRemaining < 0) {
            return 'overdue';
        }

        if ($daysRemaining === 0) {
            return 'today';
        }

        if ($daysRemaining <= 7) {
            return 'warning';
        }

        return 'scheduled';
    }

    private function resolveStatusLabel(int $daysRemaining): string
    {
        if ($daysRemaining < 0) {
            return 'Terlambat';
        }

        if ($daysRemaining === 0) {
            return 'Hari Ini';
        }

        if ($daysRemaining <= 7) {
            return 'Segera Jatuh Tempo';
        }

        return 'Terjadwal';
    }

    private function resolveStatusBadge(int $daysRemaining): string
    {
        if ($daysRemaining < 0) {
            return 'danger';
        }

        if ($daysRemaining === 0) {
            return 'dark';
        }

        if ($daysRemaining <= 7) {
            return 'warning';
        }

        return 'success';
    }
}
