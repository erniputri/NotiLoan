<?php

namespace App\Services;

use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PeminjamanService
{
    public function __construct(
        private readonly NotificationScheduleService $notificationScheduleService,
        private readonly MitraService $mitraService
    ) {
    }

    // Create dari wizard menggabungkan data tiga langkah menjadi satu transaksi simpan yang utuh.
    public function createFromWizard(array $step1, array $step2, array $step3): Peminjaman
    {
        return DB::transaction(function () use ($step1, $step2, $step3) {
            // Jika administrasi tidak diisi manual, sistem tetap punya nilai default dari persentase bunga.
            $administrasiOtomatis = $step2['pokok_pinjaman_awal'] * ($step2['bunga_persen'] / 100);
            $administrasiFinal = $step3['administrasi_awal'] ?? $administrasiOtomatis;
            $mitra = $this->mitraService->resolveOrCreate($step1);
            $this->mitraService->guardActiveLoanConflict($mitra);

            $peminjaman = Peminjaman::create([
                'mitra_id' => $mitra->id,
                'nomor_mitra' => $step1['nomor_mitra'] ?? null,
                'virtual_account_bank' => $step1['virtual_account_bank'] ?? null,
                'virtual_account' => $step1['virtual_account'] ?? null,
                'nama_mitra' => $step1['nama_mitra'],
                'kontak' => $step1['kontak'],
                'alamat' => $step1['alamat'] ?? null,
                'kabupaten' => $step1['kabupaten'] ?? null,
                'sektor' => $step1['sektor'] ?? null,
                'tgl_peminjaman' => $step2['tgl_peminjaman'],
                'tgl_jatuh_tempo' => $step2['tgl_jatuh_tempo'],
                'tgl_akhir_pinjaman' => $step2['tgl_akhir_pinjaman'],
                'lama_angsuran_bulan' => $step2['lama_angsuran_bulan'],
                'bunga_persen' => $step2['bunga_persen'],
                'pokok_pinjaman_awal' => $step2['pokok_pinjaman_awal'],
                'administrasi_awal' => $administrasiFinal,
                'no_surat_perjanjian' => $step3['no_surat_perjanjian'],
                'jaminan' => $step3['jaminan'],
                'pokok_cicilan_sd' => 0,
                'jasa_cicilan_sd' => 0,
                'pokok_sisa' => $step2['pokok_pinjaman_awal'],
                'jasa_sisa' => 0,
                'kualitas_kredit' => 'Lancar',
            ]);

            $peminjaman->syncKualitasKredit();
            $this->notificationScheduleService->syncForLoan($peminjaman);

            return $peminjaman;
        });
    }

    // Update identitas dipisah agar perubahan profil mitra tidak ikut menyentuh logika saldo pinjaman.
    public function updateIdentity(Peminjaman $peminjaman, array $data): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $loan = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            $loan = $this->mitraService->syncLoanWithMitra($loan, $data);

            $this->notificationScheduleService->syncForLoan($loan->refresh());

            return $loan;
        });
    }

    // Update terms adalah bagian sensitif karena menyentuh pokok pinjaman, tenor, dan jatuh tempo.
    public function updateLoanTerms(Peminjaman $peminjaman, array $data): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $loan = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            $this->guardPokokPinjaman($loan, (float) $data['pokok_pinjaman_awal']);

            $lama = (int) $data['lama_angsuran_bulan'];
            $tglJatuhTempo = Carbon::parse($data['tgl_peminjaman'])
                ->addMonths($lama)
                ->format('Y-m-d');

            $jumlahTerbayar = $this->jumlahTerbayar($loan);

            // Sisa pokok dihitung ulang dari nominal baru dikurangi total yang sudah pernah dibayar.
            $payload = [
                'pokok_pinjaman_awal' => $data['pokok_pinjaman_awal'],
                'tgl_peminjaman' => $data['tgl_peminjaman'],
                'lama_angsuran_bulan' => $lama,
                'tgl_jatuh_tempo' => $tglJatuhTempo,
                'tgl_akhir_pinjaman' => $tglJatuhTempo,
                'pokok_sisa' => (int) $data['pokok_pinjaman_awal'] - $jumlahTerbayar,
            ];

            if (array_key_exists('bunga_persen', $data)) {
                $payload['bunga_persen'] = $data['bunga_persen'];
            }

            $loan->update($payload);
            $loan->syncKualitasKredit();
            $this->notificationScheduleService->syncForLoan($loan->refresh());

            return $loan;
        });
    }

    // Step administrasi dipisah agar atribut pendukung bisa diubah tanpa mengubah tenor atau nilai pokok.
    public function updateAdministrative(Peminjaman $peminjaman, array $data): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $loan = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            $loan->update([
                'administrasi_awal' => $data['administrasi_awal'],
                'kualitas_kredit' => $data['kualitas_kredit'],
                'jaminan' => $data['jaminan'] ?? null,
            ]);

            $this->notificationScheduleService->syncForLoan($loan->refresh());

            return $loan;
        });
    }

    // Jalur update umum ini dipertahankan untuk kompatibilitas dengan form lama yang belum berbasis wizard.
    public function updateGeneral(Peminjaman $peminjaman, array $data): Peminjaman
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $loan = Peminjaman::lockForUpdate()->findOrFail($peminjaman->id);
            $this->guardPokokPinjaman($loan, (float) $data['pokok_pinjaman_awal']);
            $loan = $this->mitraService->syncLoanWithMitra($loan, array_merge([
                'nomor_mitra' => $loan->nomor_mitra,
                'virtual_account_bank' => $loan->virtual_account_bank,
                'virtual_account' => $loan->virtual_account,
            ], $data));

            $lama = (int) $data['lama_angsuran_bulan'];
            $tglJatuhTempo = Carbon::parse($data['tgl_peminjaman'])
                ->addMonths($lama)
                ->format('Y-m-d');
            $jumlahTerbayar = $this->jumlahTerbayar($loan);

            $loan->update([
                'kualitas_kredit' => $data['kualitas_kredit'],
                'tgl_peminjaman' => $data['tgl_peminjaman'],
                'lama_angsuran_bulan' => $lama,
                'tgl_jatuh_tempo' => $tglJatuhTempo,
                'tgl_akhir_pinjaman' => $tglJatuhTempo,
                'pokok_pinjaman_awal' => $data['pokok_pinjaman_awal'],
                'pokok_sisa' => (int) $data['pokok_pinjaman_awal'] - $jumlahTerbayar,
            ]);

            $this->notificationScheduleService->syncForLoan($loan->refresh());

            return $loan;
        });
    }

    // Pokok pinjaman baru tidak boleh lebih kecil dari total yang sudah dibayar oleh mitra.
    private function guardPokokPinjaman(Peminjaman $peminjaman, float $pokokPinjamanAwal): void
    {
        if ($pokokPinjamanAwal < $this->jumlahTerbayar($peminjaman)) {
            throw ValidationException::withMessages([
                'pokok_pinjaman_awal' => 'Pokok pinjaman tidak boleh lebih kecil dari total pembayaran yang sudah tercatat.',
            ]);
        }
    }

    // Helper ini menghitung total nominal yang sudah tercicil berdasarkan selisih pokok awal dan pokok sisa.
    private function jumlahTerbayar(Peminjaman $peminjaman): int
    {
        return (int) ($peminjaman->pokok_pinjaman_awal - $peminjaman->pokok_sisa);
    }
}
