<?php

namespace App\Console\cron;

use App\Models\Notification;
use App\Models\LogFailure;
use Exception;
use Illuminate\Support\Facades\Log;

class SendNotificationWa {

    public function __invoke() {
        Log::info('SendNotificationWa dijalankan');

        try {
            $task = Notification::where('status', 0)->limit(10)->get();

            Log::info('Jumlah notifikasi', [
                'count' => $task->count()
            ]);

            $url = config('services.wa.url');
            $headers = [
                'SecretKey:jeB4DfuH2c1kZGaldxY2',
                'Content-Type: application/json',
            ];

            foreach ($task as $item) {
                Log::info('Kirim WA ke', ['kontak' => $item->kontak]);

                $fields = [
                    'nohp' => $item->kontak,
                    'pesan' => $item->message
                ];

                $ch = curl_init();
                curl_setopt_array($ch, [
                    CURLOPT_URL => $url,
                    CURLOPT_POST => true,
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_POSTFIELDS => json_encode($fields),
                ]);

                $result = curl_exec($ch);
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                Log::info('Response WA', [
                    'httpcode' => $httpcode,
                    'result' => $result
                ]);

                if ($httpcode == 200) {
                    $item->update(['status' => 1]);
                }
            }
        } catch (Exception $e) {
            Log::error('Error SendNotificationWa', [
                'message' => $e->getMessage()
            ]);
        }
    }
}
