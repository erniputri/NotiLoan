<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicateIds = DB::table('notifications as current')
            ->join('notifications as newer', function ($join) {
                $join->on('current.peminjaman_id', '=', 'newer.peminjaman_id')
                    ->on('current.period_start', '=', 'newer.period_start')
                    ->whereColumn('current.id', '<', 'newer.id');
            })
            ->select('current.id')
            ->pluck('current.id');

        if ($duplicateIds->isNotEmpty()) {
            DB::table('notification_attempts')
                ->whereIn('notification_id', $duplicateIds)
                ->delete();

            DB::table('notifications')
                ->whereIn('id', $duplicateIds)
                ->delete();
        }

        Schema::table('notifications', function (Blueprint $table) {
            $table->unique(['peminjaman_id', 'period_start'], 'notifications_loan_period_unique');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropUnique('notifications_loan_period_unique');
        });
    }
};
