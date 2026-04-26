<?php

namespace Database\Seeders;

use App\Models\Mitra;
use App\Models\Notification;
use App\Models\NotificationAttempt;
use App\Models\Pembayaran;
use App\Models\Peminjaman;
use App\Services\NotificationScheduleService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoNotificationScenarioSeeder extends Seeder
{
    private const MONTHLY_SCENARIO_NUMBER = 'DEMO-NOTIF-001';
    private const SETTLED_SCENARIO_NUMBER = 'DEMO-NOTIF-002';
    private const MANUAL_SCENARIO_NUMBER = 'DEMO-NOTIF-003';

    public function run(): void
    {
        DB::transaction(function (): void {
            $this->cleanupExistingDemoRows();

            $this->seedMonthlyScheduleScenario();
            $this->seedSettledLoanScenario();
            $this->seedManualSecondReminderScenario();
        });
    }

    // Skenario 1: pinjaman masih aktif dan notifikasi awal bulan sudah disiapkan untuk batch bulanan.
    private function seedMonthlyScheduleScenario(): void
    {
        $mitra = Mitra::create([
            'nomor_mitra' => self::MONTHLY_SCENARIO_NUMBER,
            'virtual_account_bank' => 'Bank BRI',
            'virtual_account' => '880001234567',
            'nama_mitra' => 'Mitra Demo Jadwal Bulanan',
            'kontak' => '081210000001',
            'alamat' => 'Jl. Demo Schedule No. 1',
            'kabupaten' => 'Pekanbaru',
            'sektor' => 'Perdagangan',
        ]);

        $loan = Peminjaman::create([
            'mitra_id' => $mitra->id,
            'nomor_mitra' => $mitra->nomor_mitra,
            'virtual_account_bank' => $mitra->virtual_account_bank,
            'virtual_account' => $mitra->virtual_account,
            'nama_mitra' => $mitra->nama_mitra,
            'kontak' => $mitra->kontak,
            'alamat' => $mitra->alamat,
            'kabupaten' => $mitra->kabupaten,
            'sektor' => $mitra->sektor,
            'tgl_peminjaman' => '2026-03-30',
            'tgl_jatuh_tempo' => '2026-04-30',
            'tgl_akhir_pinjaman' => '2027-03-30',
            'lama_angsuran_bulan' => 12,
            'bunga_persen' => 6,
            'pokok_pinjaman_awal' => 1500000,
            'administrasi_awal' => 25000,
            'no_surat_perjanjian' => 'DEMO-SP-001',
            'jaminan' => 'BPKB Motor',
            'pokok_cicilan_sd' => 0,
            'jasa_cicilan_sd' => 0,
            'pokok_sisa' => 1500000,
            'jasa_sisa' => 90000,
            'kualitas_kredit' => 'Lancar',
        ]);

        app(NotificationScheduleService::class)->syncForLoan($loan);
    }

    // Skenario 2: pinjaman sudah lunas sehingga tidak lagi ikut antrean notifikasi.
    private function seedSettledLoanScenario(): void
    {
        $mitra = Mitra::create([
            'nomor_mitra' => self::SETTLED_SCENARIO_NUMBER,
            'virtual_account_bank' => 'Bank BNI',
            'virtual_account' => '990001234568',
            'nama_mitra' => 'Mitra Demo Sudah Lunas',
            'kontak' => '081210000002',
            'alamat' => 'Jl. Demo Lunas No. 2',
            'kabupaten' => 'Siak',
            'sektor' => 'Jasa',
        ]);

        $loan = Peminjaman::create([
            'mitra_id' => $mitra->id,
            'nomor_mitra' => $mitra->nomor_mitra,
            'virtual_account_bank' => $mitra->virtual_account_bank,
            'virtual_account' => $mitra->virtual_account,
            'nama_mitra' => $mitra->nama_mitra,
            'kontak' => $mitra->kontak,
            'alamat' => $mitra->alamat,
            'kabupaten' => $mitra->kabupaten,
            'sektor' => $mitra->sektor,
            'tgl_peminjaman' => '2026-02-15',
            'tgl_jatuh_tempo' => '2026-03-15',
            'tgl_akhir_pinjaman' => '2027-02-15',
            'lama_angsuran_bulan' => 12,
            'bunga_persen' => 6,
            'pokok_pinjaman_awal' => 900000,
            'administrasi_awal' => 15000,
            'no_surat_perjanjian' => 'DEMO-SP-002',
            'jaminan' => 'Sertifikat',
            'pokok_cicilan_sd' => 900000,
            'jasa_cicilan_sd' => 54000,
            'pokok_sisa' => 0,
            'jasa_sisa' => 0,
            'kualitas_kredit' => 'Lancar',
        ]);

        Pembayaran::create([
            'peminjaman_id' => $loan->id,
            'tanggal_pembayaran' => '2026-03-15',
            'jumlah_bayar' => 954000,
        ]);
    }

    // Skenario 3: notifikasi awal sudah terkirim, tetapi mitra belum bayar sehingga tombol kirim manual pengingat kedua akan muncul.
    private function seedManualSecondReminderScenario(): void
    {
        $mitra = Mitra::create([
            'nomor_mitra' => self::MANUAL_SCENARIO_NUMBER,
            'virtual_account_bank' => 'Bank Mandiri',
            'virtual_account' => '770001234569',
            'nama_mitra' => 'Mitra Demo Pengingat Kedua',
            'kontak' => '081210000003',
            'alamat' => 'Jl. Demo Reminder No. 3',
            'kabupaten' => 'Kampar',
            'sektor' => 'Pertanian',
        ]);

        $loan = Peminjaman::create([
            'mitra_id' => $mitra->id,
            'nomor_mitra' => $mitra->nomor_mitra,
            'virtual_account_bank' => $mitra->virtual_account_bank,
            'virtual_account' => $mitra->virtual_account,
            'nama_mitra' => $mitra->nama_mitra,
            'kontak' => $mitra->kontak,
            'alamat' => $mitra->alamat,
            'kabupaten' => $mitra->kabupaten,
            'sektor' => $mitra->sektor,
            'tgl_peminjaman' => '2026-03-01',
            'tgl_jatuh_tempo' => '2026-04-01',
            'tgl_akhir_pinjaman' => '2027-03-01',
            'lama_angsuran_bulan' => 12,
            'bunga_persen' => 6,
            'pokok_pinjaman_awal' => 1800000,
            'administrasi_awal' => 30000,
            'no_surat_perjanjian' => 'DEMO-SP-003',
            'jaminan' => 'BPKB Mobil',
            'pokok_cicilan_sd' => 0,
            'jasa_cicilan_sd' => 0,
            'pokok_sisa' => 1800000,
            'jasa_sisa' => 108000,
            'kualitas_kredit' => 'Kurang Lancar',
        ]);

        $notification = app(NotificationScheduleService::class)->syncForLoan($loan);

        $notification?->update([
            'status' => true,
            'sent_at' => Carbon::parse('2026-04-01 00:05:00'),
            'follow_up_sent_at' => null,
        ]);
    }

    private function cleanupExistingDemoRows(): void
    {
        $demoNumbers = [
            self::MONTHLY_SCENARIO_NUMBER,
            self::SETTLED_SCENARIO_NUMBER,
            self::MANUAL_SCENARIO_NUMBER,
        ];

        $loanIds = Peminjaman::query()
            ->whereIn('nomor_mitra', $demoNumbers)
            ->pluck('id');

        if ($loanIds->isNotEmpty()) {
            $notificationIds = Notification::query()
                ->whereIn('peminjaman_id', $loanIds)
                ->pluck('id');

            NotificationAttempt::query()
                ->whereIn('peminjaman_id', $loanIds)
                ->delete();

            if ($notificationIds->isNotEmpty()) {
                NotificationAttempt::query()
                    ->whereIn('notification_id', $notificationIds)
                    ->delete();
            }

            Pembayaran::query()
                ->whereIn('peminjaman_id', $loanIds)
                ->delete();

            Notification::query()
                ->whereIn('peminjaman_id', $loanIds)
                ->delete();

            Peminjaman::query()
                ->whereIn('id', $loanIds)
                ->delete();
        }

        Mitra::query()
            ->whereIn('nomor_mitra', $demoNumbers)
            ->delete();
    }
}
