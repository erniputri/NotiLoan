<?php

namespace App\Console\cron;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class SendNotificationWa {

    public function __invoke() {
        Log::info('SendNotificationWa wrapper dijalankan');
        Artisan::call('wa:send-notification');
    }
}
