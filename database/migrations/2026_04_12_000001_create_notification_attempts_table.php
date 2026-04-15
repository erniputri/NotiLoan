<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')
                ->nullable()
                ->constrained('notifications')
                ->nullOnDelete();
            $table->foreignId('peminjaman_id')
                ->nullable()
                ->constrained('peminjaman')
                ->nullOnDelete();
            $table->string('kontak');
            $table->text('message');
            $table->string('channel')->default('whatsapp');
            $table->string('trigger_type')->default('system');
            $table->string('send_status')->default('processing');
            $table->json('payload')->nullable();
            $table->string('response_code')->nullable();
            $table->text('response_body')->nullable();
            $table->boolean('is_success')->default(false);
            $table->timestamp('attempted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_attempts');
    }
};
