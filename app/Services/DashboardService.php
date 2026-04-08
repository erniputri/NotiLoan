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
    private const QUALITY_LABELS = [
        'Lancar',
        'Kurang Lancar',
        'Ragu-ragu',
        'Macet',
        'Tidak Diketahui',
    ];

    // Batas ini menjaga dashboard tetap ringkas dan tidak berubah menjadi halaman daftar penuh.
    private const PRIORITY_LIMIT = 3;
    private const RECENT_PAYMENT_LIMIT = 4;
    private const UPCOMING_LIMIT = 6;
    private const OVERDUE_LIMIT = 6;

    // Method utama ini mengumpulkan seluruh data ringkasan yang dipakai oleh dashboard.
    public function build(?string $chartPeriod = null): array
    {
        $today = now()->startOfDay();
        $thirtyDaysAhead = now()->copy()->addDays(30)->endOfDay();
        $sevenDaysAhead = now()->copy()->addDays(7)->endOfDay();
        $resolvedChartPeriod = $this->resolveChartPeriod($chartPeriod);

        // Statistik inti dibagi menjadi dua kelompok besar: pinjaman dan notifikasi.
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
            ->whereBetween('tgl_peminjaman', $this->chartDateRange($resolvedChartPeriod))
            ->selectRaw('COALESCE(kualitas_kredit, ?) as kualitas, COUNT(*) as total', ['Tidak Diketahui'])
            ->groupBy('kualitas')
            ->pluck('total', 'kualitas');

        $qualityBreakdown = collect(self::QUALITY_LABELS)
            ->mapWithKeys(fn (string $label) => [$label => (int) ($qualityBreakdown[$label] ?? 0)]);

        // Dataset pinjaman aktif ini menjadi sumber panel jatuh tempo, prioritas, dan modal detail.
        $activeLoans = Peminjaman::query()
            ->select([
                'id',
                'nomor_mitra',
                'nama_mitra',
                'kontak',
                'tgl_peminjaman',
                'tgl_jatuh_tempo',
                'pokok_pinjaman_awal',
                'pokok_sisa',
                'lama_angsuran_bulan',
                'bunga_persen',
                'kualitas_kredit',
            ])
            ->where('pokok_sisa', '>', 0)
            ->with([
                'latestPembayaran' => function ($query) {
                    $query->select([
                        'pembayarans.id',
                        'pembayarans.peminjaman_id',
                        'pembayarans.tanggal_pembayaran',
                        'pembayarans.jumlah_bayar',
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
            ->withCount('pembayaran')
            ->get();

        // Data mentah diubah dulu menjadi item siap tampil agar Blade tidak dibebani logika bisnis.
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
            'chartPeriod' => $resolvedChartPeriod,
            'chartPeriodLabel' => $this->chartPeriodLabel($resolvedChartPeriod),
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

    private function resolveChartPeriod(?string $chartPeriod): string
    {
        return in_array($chartPeriod, ['daily', 'weekly', 'monthly'], true)
            ? $chartPeriod
            : 'monthly';
    }

    private function chartDateRange(string $chartPeriod): array
    {
        return match ($chartPeriod) {
            'daily' => [
                now()->startOfDay()->toDateString(),
                now()->endOfDay()->toDateString(),
            ],
            'weekly' => [
                now()->startOfWeek()->toDateString(),
                now()->endOfWeek()->toDateString(),
            ],
            default => [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ],
        };
    }

    private function chartPeriodLabel(string $chartPeriod): string
    {
        return match ($chartPeriod) {
            'daily' => 'Hari ini',
            'weekly' => 'Minggu ini',
            default => 'Bulan ini',
        };
    }

    // Pagination manual dipakai karena item prioritas berasal dari collection hasil olahan, bukan query paginate langsung.
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

    // Tiap pinjaman aktif diterjemahkan menjadi satu item dashboard yang sudah punya label, status, dan hitungan turunan.
    private function transformDueItems(Collection $activeLoans, Carbon $today): Collection
    {
        return $activeLoans->map(function (Peminjaman $peminjaman) use ($today) {
            // Jika belum ada pembayaran, acuan jatuh tempo memakai tanggal pinjaman.
            // Jika sudah ada, acuan bergeser dari pembayaran terakhir.
            $referenceDate = $peminjaman->latestPembayaran?->tanggal_pembayaran ?? $peminjaman->tgl_peminjaman;
            $nextDueDate = Carbon::parse($referenceDate)->addMonth()->startOfDay();
            $daysRemaining = $today->diffInDays($nextDueDate, false);
            $completedInstallments = (int) $peminjaman->pembayaran_count;
            $remainingInstallments = max((int) $peminjaman->lama_angsuran_bulan, 0);
            $totalInstallments = $completedInstallments + $remainingInstallments;
            $currentInstallment = $remainingInstallments > 0
                ? min($completedInstallments + 1, max($totalInstallments, 1))
                : $totalInstallments;

            return [
                'id' => $peminjaman->id,
                'nomor_mitra' => $peminjaman->nomor_mitra,
                'nama_mitra' => $peminjaman->nama_mitra,
                'kontak' => $peminjaman->kontak,
                'tgl_peminjaman' => $peminjaman->tgl_peminjaman,
                'pokok_pinjaman_awal' => (int) $peminjaman->pokok_pinjaman_awal,
                'pokok_sisa' => (int) $peminjaman->pokok_sisa,
                'bunga_persen' => $peminjaman->bunga_persen,
                'kualitas_kredit' => $peminjaman->kualitas_kredit,
                'next_due_date' => $nextDueDate,
                'days_remaining' => $daysRemaining,
                'completed_installments' => $completedInstallments,
                'remaining_installments' => $remainingInstallments,
                'total_installments' => $totalInstallments,
                'current_installment' => $currentInstallment,
                'latest_payment_date' => $peminjaman->latestPembayaran?->tanggal_pembayaran,
                'latest_payment_amount' => $peminjaman->latestPembayaran?->jumlah_bayar,
                'notification_status' => $this->resolveNotificationStatus($peminjaman->notifikasi),
                'status_key' => $this->resolveStatusKey($daysRemaining),
                'status_label' => $this->resolveStatusLabel($daysRemaining),
                'status_badge' => $this->resolveStatusBadge($daysRemaining),
            ];
        });
    }

    // Status notifikasi diterjemahkan ke label ramah-baca agar view tidak perlu memahami struktur model.
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

    // Key status dipakai untuk kebutuhan logika internal seperti grouping dan perhitungan ringkasan.
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

    // Label dibuat terpisah agar pesan bisnis di UI tidak bercampur dengan perhitungan hari.
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

    // Nama badge dipisah agar perubahan warna UI tidak memengaruhi aturan hitung status.
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
