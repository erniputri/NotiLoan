<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->index('pokok_sisa', 'peminjaman_pokok_sisa_index');
            $table->index('tgl_peminjaman', 'peminjaman_tgl_peminjaman_index');
            $table->index('kualitas_kredit', 'peminjaman_kualitas_kredit_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->index('status', 'notifications_status_index');
            $table->index('send_at', 'notifications_send_at_index');
            $table->index('due_date', 'notifications_due_date_index');
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->index('tanggal_pembayaran', 'pembayarans_tanggal_pembayaran_index');
        });

        Schema::table('notification_attempts', function (Blueprint $table) {
            $table->index('attempted_at', 'notification_attempts_attempted_at_index');
            $table->index('send_status', 'notification_attempts_send_status_index');
            $table->index('trigger_type', 'notification_attempts_trigger_type_index');
        });
    }

    public function down(): void
    {
        Schema::table('notification_attempts', function (Blueprint $table) {
            $table->dropIndex('notification_attempts_attempted_at_index');
            $table->dropIndex('notification_attempts_send_status_index');
            $table->dropIndex('notification_attempts_trigger_type_index');
        });

        Schema::table('pembayarans', function (Blueprint $table) {
            $table->dropIndex('pembayarans_tanggal_pembayaran_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_status_index');
            $table->dropIndex('notifications_send_at_index');
            $table->dropIndex('notifications_due_date_index');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropIndex('peminjaman_pokok_sisa_index');
            $table->dropIndex('peminjaman_tgl_peminjaman_index');
            $table->dropIndex('peminjaman_kualitas_kredit_index');
        });
    }
};
