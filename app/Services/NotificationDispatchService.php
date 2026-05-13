<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\NotificationAttempt;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationDispatchService
{
    // Semua hit pengiriman ditampung ke tabel attempt agar proses testing dan audit bisa dibaca dari database.
    public function dispatch(Notification $notification, string $triggerType = 'system'): NotificationAttempt
    {
        return $this->dispatchMessage($notification, $notification->message, $triggerType, false);
    }

    public function dispatchSecondReminder(Notification $notification, string $triggerType = 'second_notice_system'): NotificationAttempt
    {
        $notification->loadMissing('peminjaman.latestPembayaran');

        $message = app(NotificationScheduleService::class)->buildOverdueMessage(
            $notification->peminjaman,
            $notification->peminjaman->next_due_date
        );

        return $this->dispatchMessage($notification, $message, $triggerType, true);
    }

    private function dispatchMessage(
        Notification $notification,
        string $message,
        string $triggerType,
        bool $isFollowUp
    ): NotificationAttempt
    {
        return DB::transaction(function () use ($notification, $message, $triggerType, $isFollowUp) {
            $notification->loadMissing('peminjaman');

            if ($isFollowUp && $notification->follow_up_sent_at) {
                return $notification->attempts()->create([
                    'peminjaman_id' => $notification->peminjaman_id,
                    'kontak' => $notification->kontak,
                    'message' => $message,
                    'channel' => 'whatsapp',
                    'trigger_type' => $triggerType,
                    'send_status' => 'skipped',
                    'payload' => [
                        'kontak' => $notification->kontak,
                        'message' => $message,
                    ],
                    'response_code' => 'FOLLOW_UP_ALREADY_SENT',
                    'response_body' => 'Notifikasi kedua untuk siklus jatuh tempo ini sudah pernah dikirim.',
                    'is_success' => false,
                    'attempted_at' => now(),
                ]);
            }

            $attempt = $notification->attempts()->create([
                'peminjaman_id' => $notification->peminjaman_id,
                'kontak' => $notification->kontak,
                'message' => $message,
                'channel' => 'whatsapp',
                'trigger_type' => $triggerType,
                'send_status' => 'processing',
                'payload' => [
                    'kontak' => $notification->kontak,
                    'message' => $message,
                ],
                'attempted_at' => now(),
            ]);

            $dispatchResult = $this->sendViaConfiguredDriver($notification, $message, $triggerType, $attempt);

            $attempt->update([
                'send_status' => $dispatchResult['send_status'],
                'response_code' => $dispatchResult['response_code'],
                'response_body' => $dispatchResult['response_body'],
                'payload' => $dispatchResult['payload'],
                'is_success' => $dispatchResult['is_success'],
            ]);

            if (! $dispatchResult['is_success']) {
                return $attempt->refresh();
            }

            $notification->update([
                'status' => true,
                'sent_at' => $isFollowUp ? $notification->sent_at : now(),
                'follow_up_sent_at' => $isFollowUp ? now() : $notification->follow_up_sent_at,
            ]);

            return $attempt->refresh();
        });
    }

    private function sendViaConfiguredDriver(
        Notification $notification,
        string $message,
        string $triggerType,
        NotificationAttempt $attempt
    ): array {
        return match (config('services.whatsapp.driver', 'simulator')) {
            'twilio_sandbox' => $this->sendViaTwilioSandbox($notification, $message, $triggerType, $attempt),
            default => $this->sendViaSimulator($notification, $message, $triggerType, $attempt),
        };
    }

    private function sendViaSimulator(
        Notification $notification,
        string $message,
        string $triggerType,
        NotificationAttempt $attempt
    ): array {
        Log::info('SIMULASI WA TERKIRIM', [
            'trigger' => $triggerType,
            'ke' => $notification->kontak,
            'message' => $message,
            'notification_id' => $notification->id,
            'attempt_id' => $attempt->id,
        ]);

        return [
            'send_status' => 'success',
            'response_code' => 'SIMULATED',
            'response_body' => 'Pesan berhasil diproses oleh simulator WhatsApp.',
            'payload' => [
                'driver' => 'simulator',
                'kontak' => $notification->kontak,
                'message' => $message,
            ],
            'is_success' => true,
        ];
    }

    private function sendViaTwilioSandbox(
        Notification $notification,
        string $message,
        string $triggerType,
        NotificationAttempt $attempt
    ): array {
        $config = config('services.whatsapp.twilio', []);
        $accountSid = trim((string) ($config['account_sid'] ?? ''));
        $authToken = trim((string) ($config['auth_token'] ?? ''));
        $sandboxNumber = $this->normalizeToE164($config['sandbox_number'] ?? null);
        $recipientNumber = $this->normalizeToE164($notification->kontak);
        $contentSid = trim((string) ($config['content_sid'] ?? ''));
        $caBundle = $this->resolveTwilioCaBundle($config['ca_bundle'] ?? null);

        $twilioRequestData = [
            'To' => 'whatsapp:' . $recipientNumber,
            'From' => 'whatsapp:' . $sandboxNumber,
        ];

        if ($contentSid !== '') {
            $twilioRequestData['ContentSid'] = $contentSid;
            $twilioRequestData['ContentVariables'] = json_encode(
                $this->buildTwilioContentVariables($notification),
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );
        } else {
            $twilioRequestData['Body'] = $message;
        }

        $payload = [
            'driver' => 'twilio_sandbox',
            'to' => $recipientNumber ? 'whatsapp:' . $recipientNumber : null,
            'from' => $sandboxNumber ? 'whatsapp:' . $sandboxNumber : null,
            'body' => $contentSid === '' ? $message : null,
            'content_sid' => $contentSid !== '' ? $contentSid : null,
            'content_variables' => $contentSid !== '' ? $this->buildTwilioContentVariables($notification) : null,
            'trigger' => $triggerType,
        ];

        if ($accountSid === '' || $authToken === '' || ! $sandboxNumber) {
            return [
                'send_status' => 'failed',
                'response_code' => 'TWILIO_CONFIG_MISSING',
                'response_body' => 'Credential Twilio Sandbox belum lengkap. Isi TWILIO_ACCOUNT_SID, TWILIO_AUTH_TOKEN, dan TWILIO_WHATSAPP_SANDBOX_NUMBER.',
                'payload' => $payload,
                'is_success' => false,
            ];
        }

        if (! $recipientNumber) {
            return [
                'send_status' => 'failed',
                'response_code' => 'INVALID_RECIPIENT_NUMBER',
                'response_body' => 'Nomor tujuan belum valid untuk format WhatsApp E.164.',
                'payload' => $payload,
                'is_success' => false,
            ];
        }

        try {
            $httpClient = Http::asForm()
                ->withBasicAuth($accountSid, $authToken);

            if ($caBundle) {
                $httpClient = $httpClient->withOptions([
                    'verify' => $caBundle,
                ]);
            }

            $response = $httpClient->post(
                "https://api.twilio.com/2010-04-01/Accounts/{$accountSid}/Messages.json",
                $twilioRequestData
            );
        } catch (\Throwable $exception) {
            Log::warning('TWILIO SANDBOX REQUEST FAILED', [
                'attempt_id' => $attempt->id,
                'error' => $exception->getMessage(),
            ]);

            return [
                'send_status' => 'failed',
                'response_code' => 'TWILIO_REQUEST_EXCEPTION',
                'response_body' => $exception->getMessage(),
                'payload' => $payload,
                'is_success' => false,
            ];
        }

        $body = $response->json();

        if ($response->successful()) {
            Log::info('TWILIO SANDBOX WA TERKIRIM', [
                'trigger' => $triggerType,
                'ke' => $notification->kontak,
                'notification_id' => $notification->id,
                'attempt_id' => $attempt->id,
                'twilio_sid' => $body['sid'] ?? null,
            ]);

            return [
                'send_status' => 'success',
                'response_code' => (string) ($body['sid'] ?? 'TWILIO_ACCEPTED'),
                'response_body' => (string) ($body['status'] ?? 'accepted'),
                'payload' => $payload + [
                    'twilio_response' => $body,
                ],
                'is_success' => true,
            ];
        }

        return [
            'send_status' => 'failed',
            'response_code' => (string) ($body['code'] ?? ('HTTP_' . $response->status())),
            'response_body' => (string) ($body['message'] ?? $response->body()),
            'payload' => $payload + [
                'twilio_response' => is_array($body) ? $body : $response->body(),
            ],
            'is_success' => false,
        ];
    }

    private function normalizeToE164(?string $number): ?string
    {
        if ($number === null) {
            return null;
        }

        $number = trim($number);

        if ($number === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $number);

        if (! is_string($digits) || $digits === '') {
            return null;
        }

        if (str_starts_with($number, '+')) {
            return '+' . $digits;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        if (strlen($digits) < 8 || strlen($digits) > 15) {
            return null;
        }

        return '+' . $digits;
    }

    private function buildTwilioContentVariables(Notification $notification): array
    {
        $notification->loadMissing('peminjaman');

        $dueDate = $notification->due_date instanceof CarbonInterface
            ? $notification->due_date
            : ($notification->due_date ? Carbon::parse($notification->due_date) : null);

        return [
            '1' => $dueDate?->format('d/m/Y') ?? now()->format('d/m/Y'),
            '2' => now()->format('H:i') . ' WIB',
        ];
    }

    private function resolveTwilioCaBundle(?string $configuredPath): ?string
    {
        $candidatePaths = array_filter([
            $configuredPath,
            ini_get('curl.cainfo') ?: null,
            ini_get('openssl.cafile') ?: null,
        ]);

        foreach ($candidatePaths as $candidatePath) {
            $path = trim((string) $candidatePath);

            if ($path !== '' && is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
