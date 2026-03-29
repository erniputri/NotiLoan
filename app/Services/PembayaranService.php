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
    public function create(Peminjaman $peminjaman, array $data): Pembayaran
    {
        return DB::transaction(function () use ($peminjaman, $data) {
            $loan = Peminjaman::query()
                ->lockForUpdate()
                ->findOrFail($peminjaman->id);

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

            return $pembayaran;
        });
    }

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

            return $payment;
        });
    }

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

            $proofPath = $payment->bukti_pembayaran;
            $payment->delete();
            $this->deleteProof($proofPath);
        });
    }

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

    private function guardPaymentAmount(Peminjaman $peminjaman, float $jumlahBayar): void
    {
        if ($jumlahBayar > (float) $peminjaman->pokok_sisa) {
            throw ValidationException::withMessages([
                'jumlah_bayar' => 'Jumlah bayar melebihi sisa pinjaman.',
            ]);
        }
    }

    private function storeProof(?UploadedFile $file): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store('bukti-pembayaran', 'public');
    }

    private function deleteProof(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
