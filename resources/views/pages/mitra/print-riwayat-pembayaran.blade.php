<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran Mitra - {{ $mitra->nama_mitra }}</title>
    <style>
        :root {
            --green-900: #123524;
            --green-700: #1f6f50;
            --green-100: #edf7f1;
            --green-050: #f7fbf8;
            --line: #cfe0d4;
            --line-strong: #9bb8a7;
            --text-900: #203126;
            --text-700: #4e6257;
            --text-500: #6f7f74;
            --danger: #c65050;
            --warning: #c99100;
            --info: #4d5cc7;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #eef4ef;
            color: var(--text-900);
            font-family: Arial, Helvetica, sans-serif;
        }

        .page-shell {
            max-width: 960px;
            margin: 24px auto;
            padding: 0 16px 24px;
        }

        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .toolbar-copy {
            color: var(--text-700);
            font-size: 14px;
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 1px solid var(--green-700);
            background: #fff;
            color: var(--green-700);
            padding: 10px 16px;
            border-radius: 999px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--green-700);
            color: #fff;
        }

        .document {
            background: #fff;
            border: 1px solid #dde9df;
            box-shadow: 0 18px 38px rgba(18, 53, 36, 0.08);
            padding: 28px;
        }

        .document-header {
            text-align: center;
            border-top: 2px solid var(--green-900);
            border-bottom: 2px solid var(--green-900);
            padding: 14px 0;
            margin-bottom: 24px;
        }

        .document-title {
            margin: 0;
            color: var(--green-900);
            font-size: 24px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .section {
            margin-bottom: 18px;
        }

        .section-title {
            margin: 0 0 8px;
            color: var(--green-900);
            font-size: 14px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .detail-table th,
        .detail-table td,
        .history-table th,
        .history-table td {
            border: 1px solid var(--line);
            padding: 9px 11px;
            font-size: 12px;
            vertical-align: top;
        }

        .detail-table th {
            width: 22%;
            background: var(--green-050);
            color: var(--text-700);
            text-align: left;
            font-weight: 700;
        }

        .detail-table td {
            color: var(--text-900);
            font-weight: 600;
        }

        .history-table thead th {
            background: var(--green-100);
            color: var(--green-900);
            text-align: left;
            font-size: 12px;
            font-weight: 700;
        }

        .history-table td {
            font-size: 12px;
        }

        .badge {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .badge-success {
            background: #e8f6ed;
            color: #1b7a45;
            border-color: #c6e8d2;
        }

        .badge-warning {
            background: #fff5dd;
            color: var(--warning);
            border-color: #f2dfab;
        }

        .badge-info {
            background: #eef2ff;
            color: var(--info);
            border-color: #d8dfff;
        }

        .badge-danger {
            background: #fdecec;
            color: var(--danger);
            border-color: #f3c8c8;
        }

        .empty-state {
            padding: 14px;
            border: 1px dashed var(--line-strong);
            color: var(--text-700);
            background: #fbfdfb;
            text-align: center;
            font-size: 13px;
        }

        .signature-block {
            margin-top: 28px;
        }

        .signature-heading {
            text-align: center;
            margin-bottom: 20px;
            font-size: 13px;
            color: var(--text-700);
        }

        .signature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-role {
            margin-bottom: 60px;
            font-weight: 700;
            font-size: 13px;
        }

        .signature-line {
            border-top: 1px solid var(--line-strong);
            padding-top: 8px;
            font-size: 13px;
            color: var(--text-700);
        }

        @media print {
            @page {
                size: A4 portrait;
                margin: 14mm;
            }

            body {
                background: #fff;
            }

            .page-shell {
                max-width: none;
                margin: 0;
                padding: 0;
            }

            .toolbar {
                display: none;
            }

            .document {
                border: 0;
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    @php
        $formatCurrency = fn ($value) => 'Rp ' . number_format((int) $value, 0, ',', '.');
        $formatDate = fn ($value) => $value ? \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('d F Y') : '-';
        $statusClass = function ($status) {
            return match ($status) {
                'Lancar', 'Lunas' => 'badge-success',
                'Kurang Lancar', 'Mencicil', 'Aktif' => 'badge-warning',
                'Ragu-ragu' => 'badge-info',
                'Macet', 'Belum Bayar' => 'badge-danger',
                default => 'badge-info',
            };
        };
    @endphp

    <div class="page-shell">
        <div class="toolbar">
            <div class="toolbar-copy">Gunakan menu cetak browser untuk menyimpan dokumen ini sebagai PDF.</div>
            <div class="toolbar-actions">
                <a href="{{ route('mitra.show', $mitra->id) }}" class="btn">Kembali ke Detail Mitra</a>
                <button type="button" class="btn btn-primary" onclick="window.print()">Cetak / Simpan PDF</button>
            </div>
        </div>

        <div class="document">
            <div class="document-header">
                <h1 class="document-title">Riwayat Pembayaran Mitra</h1>
            </div>

            <div class="section">
                <h2 class="section-title">A. Informasi Mitra</h2>
                <table class="detail-table">
                    <tbody>
                        <tr>
                            <th>Nomor Mitra</th>
                            <td>{{ $mitra->nomor_mitra ?: '-' }}</td>
                            <th>Tanggal Cetak</th>
                            <td>{{ $formatDate($printedAt) }}</td>
                        </tr>
                        <tr>
                            <th>Nama Mitra</th>
                            <td>{{ $mitra->nama_mitra }}</td>
                            <th>Dicetak Oleh</th>
                            <td>{{ $printedBy }}</td>
                        </tr>
                        <tr>
                            <th>Kontak</th>
                            <td>{{ $mitra->kontak ?: '-' }}</td>
                            <th>Status Pinjaman Saat Ini</th>
                            <td>
                                <span class="badge {{ $statusClass($summary['current_loan_status']) }}">
                                    {{ $summary['current_loan_status'] }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Kabupaten</th>
                            <td>{{ $mitra->kabupaten ?: '-' }}</td>
                            <th>Status Kolektibilitas</th>
                            <td>
                                <span class="badge {{ $statusClass($summary['current_loan_quality']) }}">
                                    {{ $summary['current_loan_quality'] }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Virtual Account</th>
                            <td>{{ $mitra->formatted_virtual_account ?: '-' }}</td>
                            <th></th>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">B. Ringkasan Pembayaran</h2>
                <table class="detail-table">
                    <tbody>
                        <tr>
                            <th>Total Riwayat Pembayaran</th>
                            <td>{{ $summary['payment_count'] }} kali</td>
                            <th>Tanggal Pinjam</th>
                            <td>{{ $formatDate($currentLoan?->tgl_peminjaman) }}</td>
                        </tr>
                        <tr>
                            <th>Total Sudah Dibayar</th>
                            <td>{{ $formatCurrency($summary['payment_total']) }}</td>
                            <th>Pokok Pinjaman Awal</th>
                            <td>{{ $summary['current_loan_principal'] > 0 ? $formatCurrency($summary['current_loan_principal']) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Bayar Terakhir</th>
                            <td>{{ $formatDate($summary['latest_payment']?->tanggal_pembayaran) }}</td>
                            <th>Tenor Awal</th>
                            <td>
                                {{ $summary['current_loan_initial_tenor'] !== null
                                    ? number_format((int) $summary['current_loan_initial_tenor'], 0, ',', '.') . ' bulan'
                                    : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <th>Angsuran Terakhir</th>
                            <td>{{ $summary['last_installment_number'] ? 'Ke-' . $summary['last_installment_number'] : '-' }}</td>
                            <th>Sisa Pokok</th>
                            <td>{{ $summary['current_loan_principal'] > 0 ? $formatCurrency($summary['current_loan_remaining']) : '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">C. Riwayat Pembayaran</h2>
                @if ($payments->isEmpty())
                    <div class="empty-state">Belum ada riwayat pembayaran untuk mitra ini.</div>
                @else
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th style="width: 44px;">No</th>
                                <th style="width: 140px;">Tanggal Bayar</th>
                                <th style="width: 110px;">Angsuran Ke</th>
                                <th style="width: 140px;">Nominal</th>
                                <th style="width: 160px;">Status</th>
                                <th style="width: 140px;">Sisa Pokok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $formatDate($payment->tanggal_pembayaran) }}</td>
                                    <td>{{ $payment->installment_number }}</td>
                                    <td>{{ $payment->formatted_jumlah_bayar }}</td>
                                    <td>
                                        <span class="badge {{ $statusClass($payment->loan_quality_label) }}">
                                            {{ $payment->loan_quality_label }}
                                        </span>
                                    </td>
                                    <td>{{ $formatCurrency($payment->remaining_after_payment) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <div class="signature-block">
                <div class="signature-heading">Mengetahui,</div>
                <div class="signature-grid">
                    <div class="signature-box">
                        <div class="signature-role">Admin/Koperasi</div>
                        <div class="signature-line">( {{ $printedBy }} )</div>
                    </div>
                    <div class="signature-box">
                        <div class="signature-role">Mitra</div>
                        <div class="signature-line">( {{ $mitra->nama_mitra }} )</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
