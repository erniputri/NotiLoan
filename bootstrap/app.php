<?php

use App\Http\Middleware\CheckIsLogin;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('wa:send-notification')
            ->monthlyOn(1, '00:05');
        $schedule->command('wa:send-overdue-followup')
            ->dailyAt('08:00');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
		    'checkislogin' => CheckIsLogin::class,
            'superadmin' => EnsureSuperAdmin::class,
		]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
