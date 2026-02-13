<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Notification; // WAJIB

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
       view()->composer('*', function ($view) {

        $navbarNotifications = Notification::latest()->take(5)->get();
        $navbarNotifCount = Notification::where('status', 'unread')->count();

        $view->with(compact(
            'navbarNotifications',
            'navbarNotifCount'
        ));
    });
    }
}
