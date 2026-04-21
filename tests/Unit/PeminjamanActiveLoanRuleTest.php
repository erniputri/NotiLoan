<?php

namespace Tests\Unit;

use App\Models\Mitra;
use App\Models\Peminjaman;
use App\Services\MitraService;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PeminjamanActiveLoanRuleTest extends TestCase
{
    public function test_mitra_with_active_loan_cannot_receive_second_active_loan(): void
    {
        $mitra = new Mitra([
            'nomor_mitra' => 'MTR-001',
            'nama_mitra' => 'Mitra Aktif',
            'kontak' => '081234567890',
        ]);

        $mitra->setRelation('peminjaman', new Collection([
            new Peminjaman([
                'id' => 10,
                'pokok_sisa' => 10000000,
            ]),
        ]));

        $service = app(MitraService::class);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Mitra ini masih memiliki pinjaman aktif');

        $service->guardActiveLoanConflict($mitra);
    }

    public function test_mitra_can_receive_new_loan_when_previous_loan_is_settled(): void
    {
        $mitra = new Mitra([
            'nomor_mitra' => 'MTR-002',
            'nama_mitra' => 'Mitra Lunas',
            'kontak' => '081234567891',
        ]);

        $mitra->setRelation('peminjaman', new Collection([
            new Peminjaman([
                'id' => 20,
                'pokok_sisa' => 0,
            ]),
        ]));

        $service = app(MitraService::class);

        $service->guardActiveLoanConflict($mitra);

        $this->assertTrue(true);
    }

    public function test_current_loan_is_ignored_when_updating_same_active_loan(): void
    {
        $existingLoan = new Peminjaman([
            'pokok_sisa' => 5000000,
        ]);
        $existingLoan->id = 30;

        $mitra = new Mitra([
            'nomor_mitra' => 'MTR-003',
            'nama_mitra' => 'Mitra Edit',
            'kontak' => '081234567892',
        ]);

        $mitra->setRelation('peminjaman', new Collection([$existingLoan]));

        $service = app(MitraService::class);

        $service->guardActiveLoanConflict($mitra, $existingLoan);

        $this->assertTrue(true);
    }
}
