<?php

namespace Tests\Unit;

use App\Models\Notification;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Tests\TestCase;

class PeminjamanNotificationStateTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_overdue_loan_without_follow_up_is_marked_for_second_reminder(): void
    {
        Carbon::setTestNow('2026-04-15 08:00:00');

        $loan = new Peminjaman([
            'pokok_sisa' => 500000,
            'tgl_peminjaman' => '2026-03-01',
        ]);

        $loan->setRelation('latestPembayaran', null);
        $loan->setRelation('notifikasi', new Notification([
            'status' => true,
            'due_date' => '2026-04-01',
            'sent_at' => '2026-04-01 00:05:00',
            'follow_up_sent_at' => null,
        ]));

        $this->assertTrue($loan->is_due_and_unpaid);
        $this->assertSame('Perlu Pengingat Kedua', $loan->notification_status_label);
        $this->assertSame('danger', $loan->notification_status_class);
        $this->assertTrue($loan->should_show_notification_send_action);
    }

    public function test_future_due_loan_with_first_notice_sent_stays_in_sent_state(): void
    {
        Carbon::setTestNow('2026-04-15 08:00:00');

        $loan = new Peminjaman([
            'pokok_sisa' => 500000,
            'tgl_peminjaman' => '2026-04-10',
        ]);

        $loan->setRelation('latestPembayaran', null);
        $loan->setRelation('notifikasi', new Notification([
            'status' => true,
            'due_date' => '2026-05-10',
            'sent_at' => '2026-05-01 00:05:00',
        ]));

        $this->assertFalse($loan->is_due_and_unpaid);
        $this->assertSame('Terkirim', $loan->notification_status_label);
        $this->assertSame('success', $loan->notification_status_class);
        $this->assertFalse($loan->should_show_notification_send_action);
    }

    public function test_overdue_loan_with_follow_up_sent_is_not_shown_as_pending(): void
    {
        Carbon::setTestNow('2026-04-15 08:00:00');

        $loan = new Peminjaman([
            'pokok_sisa' => 500000,
            'tgl_peminjaman' => '2026-03-01',
        ]);

        $loan->setRelation('latestPembayaran', null);
        $loan->setRelation('notifikasi', new Notification([
            'status' => true,
            'due_date' => '2026-04-01',
            'sent_at' => '2026-04-01 00:05:00',
            'follow_up_sent_at' => '2026-04-02 08:00:00',
        ]));

        $this->assertSame('Pengingat Kedua Terkirim', $loan->notification_status_label);
        $this->assertSame('success', $loan->notification_status_class);
        $this->assertFalse($loan->should_show_notification_send_action);
    }
}
