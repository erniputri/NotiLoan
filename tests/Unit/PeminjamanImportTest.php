<?php

namespace Tests\Unit;

use App\Exports\PeminjamanTemplateExport;
use App\Imports\PeminjamanImport;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PeminjamanImportTest extends TestCase
{
    public function test_template_headings_match_import_columns(): void
    {
        $export = new PeminjamanTemplateExport();

        $this->assertSame(PeminjamanImport::importColumns(), $export->headings());
        $this->assertNotContains('pokok_cicilan_sd', $export->headings());
        $this->assertNotContains('jasa_cicilan_sd', $export->headings());
        $this->assertNotContains('pokok_sisa', $export->headings());
        $this->assertNotContains('jasa_sisa', $export->headings());
        $this->assertNotContains('kualitas_kredit', $export->headings());
    }

    public function test_partial_row_from_official_template_is_reported_as_missing_value_not_missing_header(): void
    {
        $import = new PeminjamanImport();

        try {
            $import->collection(new Collection([
                collect([
                    'nomor_mitra' => 'MTR-001',
                    'virtual_account_bank' => 'Bank BRI',
                    'virtual_account' => '',
                ]),
            ]));

            $this->fail('ValidationException was not thrown.');
        } catch (ValidationException $exception) {
            $message = $exception->errors()['file'][0] ?? '';

            $this->assertStringContainsString('Kolom virtual_account wajib ada pada template dan wajib diisi pada baris 2.', $message);
            $this->assertStringNotContainsString('Header file import belum lengkap', $message);
        }
    }
}
