<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('partials._navbar', function ($view) {
            if (! Auth::check()) {
                $view->with([
                    'navbarNotifications' => collect(),
                    'navbarNotifCount' => 0,
                ]);

                return;
            }

            $navbarData = Cache::remember('navbar_notifications:v1', now()->addSeconds(30), function () {
                $activeLoans = Peminjaman::query()
                    ->select([
                        'id',
                        'nama_mitra',
                        'tgl_peminjaman',
                        'pokok_sisa',
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
                        'notifikasi:id,peminjaman_id,status,send_at,sent_at,due_date,follow_up_sent_at',
                    ])
                    ->get();

                $secondReminderActivities = $activeLoans
                    ->filter(fn (Peminjaman $loan) => $loan->notification_status_label === 'Perlu Pengingat Kedua')
                    ->sortBy('next_due_date')
                    ->map(function (Peminjaman $loan) {
                        return (object) [
                            'title' => 'Perlu pengingat kedua',
                            'description' => "{$loan->nama_mitra} belum membayar hingga {$loan->formatted_next_due_date}.",
                            'meta' => "Sisa {$loan->formatted_pokok_sisa}",
                            'url' => route('notif.index', ['search' => $loan->nama_mitra]),
                            'icon' => 'mdi mdi-bell-ring-outline text-danger',
                        ];
                    });

                $firstReminderActivities = Notification::query()
                    ->with('peminjaman:id,nama_mitra,pokok_sisa')
                    ->where('status', false)
                    ->where('send_at', '<=', now())
                    ->whereHas('peminjaman', function ($query) {
                        $query->where('pokok_sisa', '>', 0);
                    })
                    ->latest('send_at')
                    ->take(5)
                    ->get()
                    ->map(function (Notification $notification) {
                        $partnerName = $notification->peminjaman?->nama_mitra ?: 'Mitra';

                        return (object) [
                            'title' => 'Batch notifikasi siap diproses',
                            'description' => "{$partnerName} sudah masuk antrian notifikasi awal bulan.",
                            'meta' => optional($notification->send_at)->diffForHumans() ?: 'Siap diproses',
                            'url' => route('notif.index', ['search' => $partnerName]),
                            'icon' => 'mdi mdi-timer-sand text-warning',
                        ];
                    });

                return [
                    'navbarNotifications' => $secondReminderActivities
                        ->concat($firstReminderActivities)
                        ->take(5)
                        ->values(),
                    'navbarNotifCount' => $secondReminderActivities->count(),
                ];
            });

            $view->with($navbarData);
        });
    }
}
