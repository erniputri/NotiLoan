<?php

namespace App\Services;

use App\Models\Pembayaran;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PembayaranService
{
    public function __construct(
        private readonly NotificationScheduleService $notificationScheduleService
    ) {
    }

    // Create pembayaran wajib dibungkus transaction agar saldo pinjaman dan record pembayaran selalu sinkron.
    public function create(Peminjaman $peminjaman, array $data): Pembayaran
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $loan = Peminjaman::query()
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

            // Dua guard ini mencegah kasus umum yang bisa merusak data pinjaman.
            $this->guardPaymentWindow($loan, $data['tanggal_pembayaran'], $data['force'] ?? false);
            $this->guardPaymentAmount($loan, $data['jumlah_bayar']);

            $pembayaran = $loan->pembayaran()->create([
                'tanggal_pembayaran' => $data['tanggal_pembayaran'],
                'jumlah_bayar' => $data['jumlah_bayar'],
                'bukti_pembayaran' => $this->storeProof($data['bukti_pembayaran'] ?? null),
            ]);

            $loan->pokok_sisa -= (int) $data['jumlah_bayar'];
            $loan->lama_angsuran_bulan = max(0, (int) $loan->lama_angsuran_bulan - 1);
            $loan->syncKualitasKredit();
            $this->notificationScheduleService->syncForLoan($loan->refresh());

            return $pembayaran;
        });
    }

    // Update pembayaran menghitung ulang saldo dengan cara mengembalikan nominal lama lalu menerapkan nominal baru.
    public function update(Pembayaran $pembayaran, array $data): Pembayaran
    {
        return DB::transaction(function () use ($pembayaran, $data) {
            $payment = Pembayaran::query()
                ->with('peminjaman')
                ->lockForUpdate()
                ->findOrFail($pembayaran->id);

            $loan = Peminjaman::query()
                ->lockForUpdate()
                ->findOrFail($payment->peminjaman_id);

            $restoredSaldo = (int) $loan->pokok_sisa + (int) $payment->jumlah_bayar;

            if ((float) $data['jumlah_bayar'] > $restoredSaldo) {
                throw ValidationException::withMessages([
                    'jumlah_bayar' => 'Jumlah melebihi sisa pinjaman.',
                ]);
            }

            if (! empty($data['bukti_pembayaran'])) {
                $this->deleteProof($payment->bukti_pembayaran);
                $payment->bukti_pembayaran = $this->storeProof($data['bukti_pembayaran']);
            }

            $payment->tanggal_pembayaran = $data['tanggal_pembayaran'];
            $payment->jumlah_bayar = $data['jumlah_bayar'];
            $payment->save();

            $loan->pokok_sisa = $restoredSaldo - (int) $data['jumlah_bayar'];
            $loan->syncKualitasKredit();
            $this->notificationScheduleService->syncForLoan($loan->refresh());

            return $payment;
        });
    }

    // Delete pembayaran tidak cukup menghapus baris, tetapi juga harus memulihkan saldo dan tenor pinjaman.
    public function delete(Pembayaran $pembayaran): void
    {
        DB::transaction(function () use ($pembayaran) {
            $payment = Pembayaran::query()
                ->lockForUpdate()
                ->findOrFail($pembayaran->id);

            $loan = Peminjaman::query()
                ->lockForUpdate()
                ->findOrFail($payment->peminjaman_id);

            $loan->pokok_sisa += (int) $payment->jumlah_bayar;
            $loan->lama_angsuran_bulan += 1;
            $loan->syncKualitasKredit();
            $this->notificationScheduleService->syncForLoan($loan->refresh());

            $proofPath = $payment->bukti_pembayaran;
            $payment->delete();
            $this->deleteProof($proofPath);
        });
    }

    // Guard ini dipakai untuk menahan pembayaran yang terlalu dekat dengan transaksi sebelumnya.
    private function guardPaymentWindow(Peminjaman $peminjaman, string $paymentDate, bool $force = false): void
    {
        if ($force) {
            return;
        }

        $lastPayment = $peminjaman->pembayaran()
            ->latest('tanggal_pembayaran')
            ->first();

        if (! $lastPayment) {
            return;
        }

        $selectedPaymentDate = Carbon::parse($paymentDate);
        $daysDiff = Carbon::parse($lastPayment->tanggal_pembayaran)->diffInDays($selectedPaymentDate);

        if ($daysDiff <= 30) {
            throw ValidationException::withMessages([
                'payment_window' => 'Mitra ini sudah melakukan pembayaran dalam 30 hari terakhir.',
            ]);
        }
    }

    // Nominal pembayaran dijaga agar tidak pernah melebihi sisa pokok yang tersedia.
    private function guardPaymentAmount(Peminjaman $peminjaman, float $jumlahBayar): void
    {
        if ($jumlahBayar > (float) $peminjaman->pokok_sisa) {
            throw ValidationException::withMessages([
                'jumlah_bayar' => 'Jumlah bayar melebihi sisa pinjaman.',
            ]);
        }
    }

    // Helper kecil ini menyimpan file bukti bayar agar method utama tetap fokus pada aturan bisnis.
    private function storeProof(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store('bukti-pembayaran', 'public');
    }

    // File lama dibersihkan supaya storage tidak dipenuhi bukti yang sudah tidak dipakai.
    private function deleteProof(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
