<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\NotificationAttempt;
use App\Models\Peminjaman;
use App\Models\User;
use App\Services\NotificationDispatchService;
use App\Services\NotificationScheduleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class NotificationFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $databaseConfig = $this->resolveDatabaseConfigFromEnvironmentFile();

        config()->set('database.default', 'mysql_testing');
        config()->set('database.connections.mysql_testing', [
            'driver' => 'mysql',
            'host' => $databaseConfig['DB_HOST'] ?? '127.0.0.1',
            'port' => (int) ($databaseConfig['DB_PORT'] ?? 3306),
            'database' => $databaseConfig['DB_DATABASE'] ?? 'Notiloan',
            'username' => $databaseConfig['DB_USERNAME'] ?? 'root',
            'password' => $databaseConfig['DB_PASSWORD'] ?? '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ]);

        $this->app['db']->purge('mysql_testing');
        $this->app['db']->reconnect('mysql_testing');
        DB::connection('mysql_testing')->beginTransaction();
    }

    protected function tearDown(): void
    {
        DB::connection('mysql_testing')->rollBack();
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_monthly_schedule_sends_notification_for_active_loan(): void
    {
        Carbon::setTestNow('2026-04-01 00:05:00');

        $loan = $this->createLoan([
            'nama_mitra' => 'Mitra Aktif Bulanan',
            'kontak' => '081234567890',
            'tgl_peminjaman' => '2026-03-10',
            'pokok_pinjaman_awal' => 1000000,
            'pokok_sisa' => 1000000,
            'virtual_account_bank' => 'Bank BRI',
            'virtual_account' => '9988776655',
        ]);

        $notificationScheduleService = app(NotificationScheduleService::class);
        $notificationDispatchService = app(NotificationDispatchService::class);

        $preparedNotifications = $notificationScheduleService->prepareMonthlyNotifications(now());
        $readyNotifications = $notificationScheduleService->firstRemindersReadyForDispatch(now());

        foreach ($readyNotifications as $notification) {
            $notificationDispatchService->dispatch($notification, 'first_notice_system');
        }

        $preparedForLoan = $preparedNotifications->firstWhere('peminjaman_id', $loan->id);
        $readyForLoan = $readyNotifications->firstWhere('peminjaman_id', $loan->id);

        $notification = Notification::where('peminjaman_id', $loan->id)->first();
        $attempt = NotificationAttempt::where('peminjaman_id', $loan->id)->first();

        $this->assertNotNull($preparedForLoan);
        $this->assertNotNull($readyForLoan);
        $this->assertNotNull($notification);
        $this->assertTrue($notification->status);
        $this->assertSame('2026-04-10', $notification->due_date?->format('Y-m-d'));
        $this->assertNotNull($notification->sent_at);
        $this->assertNotNull($attempt);
        $this->assertSame('first_notice_system', $attempt->trigger_type);
        $this->assertSame('success', $attempt->send_status);
        $this->assertTrue($attempt->is_success);
        $this->assertStringContainsString('Virtual Account Bank BRI - 9988776655', $attempt->message);
    }

    public function test_settled_loan_is_excluded_from_monthly_notification_batch(): void
    {
        Carbon::setTestNow('2026-04-01 00:05:00');

        $loan = $this->createLoan([
            'nama_mitra' => 'Mitra Lunas',
            'tgl_peminjaman' => '2026-03-12',
            'pokok_pinjaman_awal' => 800000,
            'pokok_sisa' => 0,
        ]);

        $notificationScheduleService = app(NotificationScheduleService::class);
        $preparedNotifications = $notificationScheduleService->prepareMonthlyNotifications(now());
        $readyNotifications = $notificationScheduleService->firstRemindersReadyForDispatch(now());

        $this->assertNull($preparedNotifications->firstWhere('peminjaman_id', $loan->id));
        $this->assertNull($readyNotifications->firstWhere('peminjaman_id', $loan->id));
        $this->assertDatabaseMissing('notifications', [
            'peminjaman_id' => $loan->id,
        ]);
        $this->assertDatabaseMissing('notification_attempts', [
            'peminjaman_id' => $loan->id,
        ]);
    }

    public function test_manual_second_notification_is_sent_for_overdue_unpaid_loan(): void
    {
        Carbon::setTestNow('2026-04-15 08:00:00');

        $this->withoutMiddleware(VerifyCsrfToken::class);

        $user = User::factory()->create();
        $loan = $this->createLoan([
            'nama_mitra' => 'Mitra Overdue',
            'kontak' => '081299998888',
            'tgl_peminjaman' => '2026-03-01',
            'pokok_pinjaman_awal' => 1500000,
            'pokok_sisa' => 700000,
            'virtual_account_bank' => 'Bank BNI',
            'virtual_account' => '1234500099',
        ]);

        $this->actingAs($user)
            ->from(route('notif.index'))
            ->post(route('notif.send', $loan->id))
            ->assertRedirect(route('notif.index'))
            ->assertSessionHas('success');

        $notification = Notification::where('peminjaman_id', $loan->id)->first();
        $attempt = NotificationAttempt::where('peminjaman_id', $loan->id)->first();

        $this->assertNotNull($notification);
        $this->assertNotNull($notification->follow_up_sent_at);
        $this->assertNotNull($attempt);
        $this->assertSame('second_notice_manual', $attempt->trigger_type);
        $this->assertSame('success', $attempt->send_status);
        $this->assertSame('SIMULATED', $attempt->response_code);
        $this->assertTrue($attempt->is_success);
        $this->assertStringContainsString('telah jatuh tempo', $attempt->message);
        $this->assertStringContainsString('1234500099', $attempt->message);
    }

    // Helper ini menyiapkan row pinjaman minimal yang valid agar fokus test tetap pada flow notifikasi.
    private function createLoan(array $overrides = []): Peminjaman
    {
        return Peminjaman::create(array_merge([
            'nomor_mitra' => 'MTR-001',
            'nama_mitra' => 'Mitra Testing',
            'kontak' => '081234567890',
            'alamat' => 'Jl. Test',
            'kabupaten' => 'Pekanbaru',
            'sektor' => 'Perdagangan',
            'tgl_peminjaman' => '2026-03-01',
            'tgl_jatuh_tempo' => '2026-04-01',
            'tgl_akhir_pinjaman' => '2027-03-01',
            'lama_angsuran_bulan' => 12,
            'bunga_persen' => 6,
            'pokok_pinjaman_awal' => 1000000,
            'administrasi_awal' => 25000,
            'pokok_cicilan_sd' => 0,
            'jasa_cicilan_sd' => 0,
            'pokok_sisa' => 1000000,
            'jasa_sisa' => 50000,
            'no_surat_perjanjian' => 'SP-TEST-001',
            'jaminan' => 'BPKB',
            'virtual_account_bank' => 'Bank BRI',
            'virtual_account' => '9988776655',
        ], $overrides));
    }

    private function resolveDatabaseConfigFromEnvironmentFile(): array
    {
        $databaseConfig = [];
        $lines = file(base_path('.env'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $databaseConfig[trim($key)] = trim($value, " \t\n\r\0\x0B\"'");
        }

        return $databaseConfig;
    }
}
