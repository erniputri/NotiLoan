<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mitras', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_mitra', 50)->nullable()->index();
            $table->string('virtual_account_bank', 100)->nullable();
            $table->string('virtual_account', 50)->nullable();
            $table->string('nama_mitra');
            $table->string('kontak', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('kabupaten', 100)->nullable();
            $table->string('sektor', 50)->nullable();
            $table->timestamps();
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            $table->foreignId('mitra_id')
                ->nullable()
                ->after('id')
                ->constrained('mitras')
                ->nullOnDelete();
        });

        $existingLoans = DB::table('peminjaman')
            ->select([
                'id',
                'nomor_mitra',
                'virtual_account_bank',
                'virtual_account',
                'nama_mitra',
                'kontak',
                'alamat',
                'kabupaten',
                'sektor',
                'created_at',
                'updated_at',
            ])
            ->orderBy('id')
            ->get();

        foreach ($existingLoans as $loan) {
            $mitraQuery = DB::table('mitras');

            if (! empty($loan->nomor_mitra)) {
                $mitraQuery->where('nomor_mitra', $loan->nomor_mitra);
            } else {
                $mitraQuery
                    ->where('nama_mitra', $loan->nama_mitra)
                    ->where('kontak', $loan->kontak);
            }

            $existingMitra = $mitraQuery->first();

            if ($existingMitra) {
                $mitraId = $existingMitra->id;
            } else {
                $mitraId = DB::table('mitras')->insertGetId([
                    'nomor_mitra' => $loan->nomor_mitra,
                    'virtual_account_bank' => $loan->virtual_account_bank,
                    'virtual_account' => $loan->virtual_account,
                    'nama_mitra' => $loan->nama_mitra,
                    'kontak' => $loan->kontak,
                    'alamat' => $loan->alamat,
                    'kabupaten' => $loan->kabupaten,
                    'sektor' => $loan->sektor,
                    'created_at' => $loan->created_at,
                    'updated_at' => $loan->updated_at,
                ]);
            }

            DB::table('peminjaman')
                ->where('id', $loan->id)
                ->update(['mitra_id' => $mitraId]);
        }
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mitra_id');
        });

        Schema::dropIfExists('mitras');
    }
};
