<?php

namespace App\Console;

use App\Console\cron\ReminderDinner;
use App\Console\cron\ReminderLunch;
use App\Console\cron\SendNotificationWa;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(new ReminderDinner())->dailyAt('14:00');
        // $schedule->call(new ReminderLunch())->dailyAt('16:00');
        $schedule->call(new SendNotificationWa())->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
