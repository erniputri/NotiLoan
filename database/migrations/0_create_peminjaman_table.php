<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();

            // Identitas Mitra
            $table->string('nomor_mitra', 50)->nullable();
            $table->string('nama_mitra');
            $table->string('kontak', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kabupaten', 100)->nullable();

            // Kredit
            $table->date('tgl_peminjaman');
            $table->date('tgl_jatuh_tempo')->nullable();
            $table->integer('lama_angsuran_bulan')->default(0);
            $table->decimal('bunga_persen', 5, 2)->default(0);

            // Nilai Keuangan
            $table->bigInteger('pokok_pinjaman_awal');
            $table->bigInteger('administrasi_awal')->default(0);

            $table->bigInteger('pokok_cicilan_sd')->default(0);
            $table->bigInteger('jasa_cicilan_sd')->default(0);

            $table->bigInteger('pokok_sisa')->default(0);
            $table->bigInteger('jasa_sisa')->default(0);

            // Klasifikasi
            $table->string('sektor', 50)->nullable();
            $table->string('kualitas_kredit', 30)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
