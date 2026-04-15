<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('notifications', 'due_date')) {
                $table->date('due_date')->nullable()->after('message');
            }

            if (! Schema::hasColumn('notifications', 'follow_up_sent_at')) {
                $table->timestamp('follow_up_sent_at')->nullable()->after('sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $columnsToDrop = collect(['due_date', 'follow_up_sent_at'])
                ->filter(fn (string $column) => Schema::hasColumn('notifications', $column))
                ->all();

            if ($columnsToDrop !== []) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
