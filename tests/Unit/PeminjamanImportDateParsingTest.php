<?php

namespace Tests\Unit;

use App\Imports\PeminjamanImport;
use Carbon\Carbon;
use Tests\TestCase;

class PeminjamanImportDateParsingTest extends TestCase
{
    public function test_excel_serial_date_is_parsed_correctly(): void
    {
        $import = new class extends PeminjamanImport
        {
            public function parseDateForTest(mixed $value): Carbon
            {
                return $this->parseDateValue($value, 'tgl_peminjaman', 0);
            }
        };

        $parsedDate = $import->parseDateForTest(46643);

        $this->assertSame('2027-09-13', $parsedDate->format('Y-m-d'));
    }

    public function test_string_date_is_parsed_correctly(): void
    {
        $import = new class extends PeminjamanImport
        {
            public function parseDateForTest(mixed $value): Carbon
            {
                return $this->parseDateValue($value, 'tgl_peminjaman', 0);
            }
        };

        $parsedDate = $import->parseDateForTest('2026-05-13');

        $this->assertSame('2026-05-13', $parsedDate->format('Y-m-d'));
    }
}
