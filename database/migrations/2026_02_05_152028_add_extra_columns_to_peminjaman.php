<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('virtual_account', 50)->nullable()->after('nomor_mitra');
            $table->date('tgl_akhir_pinjaman')->nullable()->after('tgl_jatuh_tempo');
            $table->string('no_surat_perjanjian', 100)->nullable()->after('lama_angsuran_bulan');
            $table->string('jaminan')->nullable()->after('administrasi_awal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            //
        });
    }
};
