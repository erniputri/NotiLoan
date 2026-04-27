<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->date('period_start')
                ->nullable()
                ->after('due_date');
            $table->index(['peminjaman_id', 'period_start'], 'notifications_loan_period_index');
        });

        DB::table('notifications')
            ->select(['id', 'send_at', 'due_date'])
            ->orderBy('id')
            ->chunkById(100, function ($notifications): void {
                foreach ($notifications as $notification) {
                    $referenceDate = $notification->send_at
                        ? Carbon::parse($notification->send_at)
                        : ($notification->due_date ? Carbon::parse($notification->due_date) : null);

                    if (! $referenceDate) {
                        continue;
                    }

                    DB::table('notifications')
                        ->where('id', $notification->id)
                        ->update([
                            'period_start' => $referenceDate->copy()->startOfMonth()->toDateString(),
                        ]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('notifications_loan_period_index');
            $table->dropColumn('period_start');
        });
    }
};
