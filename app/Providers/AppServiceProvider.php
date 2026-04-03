<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
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

            $navbarNotifications = Notification::latest()
                ->take(5)
                ->get();

            $navbarNotifCount = Notification::where('status', false)->count();

            $view->with(compact('navbarNotifications', 'navbarNotifCount'));
        });
    }
}
