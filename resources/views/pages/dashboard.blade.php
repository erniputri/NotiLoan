@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper dashboard-page">
            <style>
                .dashboard-page .hero-panel {
                    background: linear-gradient(135deg, #123524 0%, #1f6f50 58%, #d7efe4 100%);
                    border-radius: 24px;
                    padding: 28px;
                    color: #fff;
                    box-shadow: 0 18px 38px rgba(18, 53, 36, 0.18);
                }

                .dashboard-page .hero-kicker {
                    font-size: 12px;
                    letter-spacing: 0.18em;
                    text-transform: uppercase;
                    opacity: 0.82;
                    margin-bottom: 10px;
                }

                .dashboard-page .hero-title {
                    font-size: 30px;
                    font-weight: 700;
                    margin-bottom: 8px;
                }

                .dashboard-page .hero-copy {
                    max-width: 620px;
                    color: rgba(255, 255, 255, 0.82);
                    margin-bottom: 0;
                }

                .dashboard-page .hero-stat-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 14px;
                }

                .dashboard-page .hero-stat {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.12);
                    border-radius: 18px;
                    padding: 16px 18px;
                    backdrop-filter: blur(6px);
                }

                .dashboard-page .hero-stat-label {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                    color: rgba(255, 255, 255, 0.72);
                }

                .dashboard-page .hero-stat-value {
                    font-size: 28px;
                    font-weight: 700;
                    margin-top: 4px;
                    margin-bottom: 4px;
                }

                .dashboard-page .hero-stat-meta {
                    font-size: 13px;
                    color: rgba(255, 255, 255, 0.76);
                    margin-bottom: 0;
                }

                .dashboard-page .metric-card {
                    border: 0;
                    border-radius: 20px;
                    height: 100%;
                    box-shadow: 0 12px 30px rgba(20, 30, 58, 0.08);
                }

                .dashboard-page .metric-card .card-body {
                    padding: 20px;
                }

                .dashboard-page .metric-topline {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    margin-bottom: 18px;
                }

                .dashboard-page .metric-label {
                    font-size: 13px;
                    font-weight: 600;
                    color: #6c757d;
                    text-transform: uppercase;
                    letter-spacing: 0.06em;
                }

                .dashboard-page .metric-icon {
                    width: 42px;
                    height: 42px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 12px;
                    font-size: 20px;
                }

                .dashboard-page .metric-value {
                    font-size: 34px;
                    font-weight: 700;
                    color: #1d2433;
                    line-height: 1;
                    margin-bottom: 10px;
                }

                .dashboard-page .metric-meta {
                    margin-bottom: 0;
                    color: #6c757d;
                    font-size: 14px;
                }

                .dashboard-page .metric-meta strong {
                    color: #1d2433;
                }

                .dashboard-page .surface-card {
                    border: 0;
                    border-radius: 22px;
                    box-shadow: 0 12px 30px rgba(20, 30, 58, 0.08);
                    height: 100%;
                }

                .dashboard-page .surface-card .card-body {
                    padding: 22px;
                }

                .dashboard-page .section-heading {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    margin-bottom: 18px;
                }

                .dashboard-page .section-heading h4,
                .dashboard-page .section-heading h5 {
                    margin-bottom: 0;
                    font-weight: 700;
                    color: #202633;
                }

                .dashboard-page .section-caption {
                    color: #6c757d;
                    font-size: 14px;
                    margin-bottom: 0;
                }

                .dashboard-page .mini-stat-list {
                    display: grid;
                    gap: 12px;
                }

                .dashboard-page .mini-stat-item {
                    border: 1px solid #edf1f7;
                    border-radius: 16px;
                    padding: 14px 16px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .dashboard-page .mini-stat-item span {
                    color: #6c757d;
                    font-size: 14px;
                }

                .dashboard-page .mini-stat-item strong {
                    font-size: 20px;
                    color: #202633;
                }

                .dashboard-page .priority-list {
                    display: grid;
                    gap: 10px;
                }

                .dashboard-page .section-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 6px 10px;
                    border-radius: 999px;
                    background: #f3f6fb;
                    color: #566074;
                    font-size: 12px;
                    font-weight: 600;
                    white-space: nowrap;
                }

                .dashboard-page .priority-item {
                    border: 1px solid #edf1f7;
                    border-radius: 16px;
                    padding: 14px 16px;
                }

                .dashboard-page .priority-head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 14px;
                }

                .dashboard-page .priority-name {
                    margin-bottom: 2px;
                    font-size: 15px;
                    font-weight: 700;
                    color: #202633;
                }

                .dashboard-page .priority-subtitle {
                    margin-bottom: 0;
                    color: #6c757d;
                    font-size: 13px;
                }

                .dashboard-page .priority-meta {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 10px;
                    margin-top: 12px;
                }

                .dashboard-page .priority-meta-card {
                    background: #f7f9fc;
                    border-radius: 14px;
                    padding: 10px 12px;
                }

                .dashboard-page .priority-meta-card span {
                    display: block;
                    font-size: 12px;
                    color: #6c757d;
                    margin-bottom: 2px;
                }

                .dashboard-page .priority-meta-card strong {
                    color: #202633;
                    font-size: 14px;
                }

                .dashboard-page .activity-table th {
                    border-top: 0;
                    font-size: 12px;
                    letter-spacing: 0.05em;
                    text-transform: uppercase;
                    color: #8a90a2;
                }

                .dashboard-page .activity-table td {
                    vertical-align: middle;
                    color: #2d3548;
                }

                .dashboard-page .action-col {
                    width: 120px;
                    text-align: right;
                }

                .dashboard-page .table-shell {
                    border: 1px solid #edf1f7;
                    border-radius: 18px;
                    overflow: hidden;
                }

                .dashboard-page .section-note {
                    margin-top: 14px;
                    margin-bottom: 0;
                    color: #7c8599;
                    font-size: 13px;
                }

                .dashboard-page .section-pagination {
                    margin-top: 14px;
                    display: flex;
                    justify-content: flex-end;
                }

                .dashboard-page .section-pagination .pagination {
                    margin-bottom: 0;
                }

                .dashboard-page .empty-state {
                    border: 1px dashed #d9e2ef;
                    border-radius: 18px;
                    padding: 28px 18px;
                    text-align: center;
                    color: #7c8599;
                    background: #fafcff;
                }

                .dashboard-page .chart-shell {
                    height: 280px;
                }

                .dashboard-page .detail-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 12px;
                }

                .dashboard-page .detail-card {
                    background: #f7fbf8;
                    border: 1px solid #e1eee6;
                    border-radius: 16px;
                    padding: 14px 16px;
                }

                .dashboard-page .detail-card span {
                    display: block;
                    font-size: 12px;
                    color: #6c757d;
                    margin-bottom: 4px;
                    text-transform: uppercase;
                    letter-spacing: 0.04em;
                }

                .dashboard-page .detail-card strong {
                    color: #202633;
                    font-size: 15px;
                }

                .dashboard-page .detail-highlight {
                    background: linear-gradient(135deg, #eef8f2, #dff1e7);
                    border: 1px solid #cae6d5;
                    border-radius: 18px;
                    padding: 16px 18px;
                    margin-bottom: 16px;
                }

                .dashboard-page .detail-highlight h6 {
                    margin-bottom: 6px;
                    font-size: 18px;
                    font-weight: 700;
                    color: #184b33;
                }

                .dashboard-page .detail-highlight p {
                    margin-bottom: 0;
                    color: #476756;
                    font-size: 14px;
                }

                .dashboard-page .modal-content {
                    border: 0;
                    border-radius: 24px;
                    overflow: hidden;
                    box-shadow: 0 24px 60px rgba(18, 53, 36, 0.18);
                }

                .dashboard-page .modal-header {
                    background: linear-gradient(135deg, #123524 0%, #1f6f50 100%);
                    color: #fff;
                    border-bottom: 0;
                    padding: 18px 22px;
                }

                .dashboard-page .modal-header .close {
                    color: #fff;
                    opacity: 0.85;
                    text-shadow: none;
                }

                .dashboard-page .modal-body {
                    padding: 22px;
                }

                @media (max-width: 991.98px) {
                    .dashboard-page .hero-panel {
                        padding: 22px;
                    }

                    .dashboard-page .hero-stat-grid,
                    .dashboard-page .priority-meta,
                    .dashboard-page .detail-grid {
                        grid-template-columns: 1fr;
                    }
                }
            </style>

            <div class="row mb-4">
                <div class="col-12">
                    <div class="hero-panel">
                        <div class="row align-items-center">
                            <div class="col-xl-7 mb-4 mb-xl-0">
                                <p class="hero-kicker">Dashboard TJSL PTPN IV REGIONAL III</p>
                                <h3 class="hero-title">Halo, {{ Auth::user()->name }}</h3>
                                <p class="hero-copy">
                                    {{-- Fokus utama hari ini ada pada pinjaman aktif, antrean notifikasi, dan mitra yang
                                    mendekati atau melewati jatuh tempo angsuran bulanannya. --}}
                                </p>
                            </div>
                            <div class="col-xl-5">
                                <div class="hero-stat-grid">
                                    <div class="hero-stat">
                                        <p class="hero-stat-label">Pinjaman Aktif</p>
                                        <div class="hero-stat-value">{{ $loanStats['active'] }}</div>
                                        <p class="hero-stat-meta">{{ $loanStats['settled'] }} pinjaman sudah lunas</p>
                                    </div>
                                    <div class="hero-stat">
                                        <p class="hero-stat-label">Notifikasi Pending</p>
                                        <div class="hero-stat-value">{{ $notificationStats['pending'] }}</div>
                                        <p class="hero-stat-meta">{{ $notificationStats['sent'] }} notifikasi sudah terkirim</p>
                                    </div>
                                    <div class="hero-stat">
                                        <p class="hero-stat-label">Jatuh Tempo 7 Hari</p>
                                        <div class="hero-stat-value">{{ $dueStats['upcoming_7_days'] }}</div>
                                        <p class="hero-stat-meta">Prioritas tindak lanjut cepat</p>
                                    </div>
                                    <div class="hero-stat">
                                        <p class="hero-stat-label">Terlambat</p>
                                        <div class="hero-stat-value">{{ $dueStats['overdue'] }}</div>
                                        <p class="hero-stat-meta">Perlu perhatian kolektibilitas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="metric-topline">
                                <span class="metric-label">Total Pinjaman</span>
                                <span class="metric-icon" style="background:#e8f3ff;color:#266dd3;">
                                    <i class="mdi mdi-database"></i>
                                </span>
                            </div>
                            <div class="metric-value">{{ $loanStats['total'] }}</div>
                            <p class="metric-meta">Terdiri dari <strong>{{ $loanStats['active'] }}</strong> pinjaman aktif.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="metric-topline">
                                <span class="metric-label">Angsuran Hari Ini</span>
                                <span class="metric-icon" style="background:#fbeed8;color:#c98012;">
                                    <i class="mdi mdi-calendar-today"></i>
                                </span>
                            </div>
                            <div class="metric-value">{{ $dueStats['today'] }}</div>
                            <p class="metric-meta">Pinjaman yang jatuh tepat pada hari ini.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="metric-topline">
                                <span class="metric-label">Jatuh Tempo 30 Hari</span>
                                <span class="metric-icon" style="background:#e4f7ee;color:#1a8f5e;">
                                    <i class="mdi mdi-timer-sand"></i>
                                </span>
                            </div>
                            <div class="metric-value">{{ $dueStats['upcoming_30_days'] }}</div>
                            <p class="metric-meta">Daftar terdekat yang perlu disiapkan notifikasinya.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="metric-topline">
                                <span class="metric-label">Notifikasi Terkirim</span>
                                <span class="metric-icon" style="background:#efe9ff;color:#6f42c1;">
                                    <i class="mdi mdi-bell-check"></i>
                                </span>
                            </div>
                            <div class="metric-value">{{ $notificationStats['sent'] }}</div>
                            <p class="metric-meta">Dari total <strong>{{ $notificationStats['total'] }}</strong> notifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-7 mb-4">
                    <div class="card surface-card">
                        <div class="card-body">
                            <div class="section-heading">
                                <div>
                                    <h4>Distribusi Kualitas Kredit</h4>
                                    <p class="section-caption">Komposisi kualitas kredit seluruh pinjaman yang tercatat.</p>
                                </div>
                            </div>
                            <div class="chart-shell">
                                <canvas id="kualitasKreditChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-5 mb-4">
                    <div class="card surface-card">
                        <div class="card-body">
                            <div class="section-heading">
                                <div>
                                    <h4>Ringkasan Prioritas</h4>
                                    <p class="section-caption">Angka cepat untuk keputusan operasional harian.</p>
                                </div>
                            </div>

                            <div class="mini-stat-list">
                                <div class="mini-stat-item">
                                    <span>Mitra terlambat</span>
                                    <strong>{{ $dueStats['overdue'] }}</strong>
                                </div>
                                <div class="mini-stat-item">
                                    <span>Jatuh tempo dalam 7 hari</span>
                                    <strong>{{ $dueStats['upcoming_7_days'] }}</strong>
                                </div>
                                <div class="mini-stat-item">
                                    <span>Notifikasi masih menunggu</span>
                                    <strong>{{ $notificationStats['pending'] }}</strong>
                                </div>
                                <div class="mini-stat-item">
                                    <span>Pinjaman sudah lunas</span>
                                    <strong>{{ $loanStats['settled'] }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-7 mb-4">
                    <div class="card surface-card">
                        <div class="card-body">
                            <div class="section-heading">
                                <div>
                                    <h5>Perlu Tindakan Cepat</h5>
                                    <p class="section-caption">Daftar mitra yang sudah terlambat atau mendekati jatuh tempo.</p>
                                </div>
                                <span class="section-chip">
                                    3 item per halaman
                                </span>
                            </div>

                            @if ($priorityItems->count() === 0)
                                <div class="empty-state">
                                    Tidak ada pinjaman yang membutuhkan perhatian segera.
                                </div>
                            @else
                                <div class="priority-list">
                                    @foreach ($priorityItems as $item)
                                        <div class="priority-item">
                                            <div class="priority-head">
                                                <div class="flex-grow-1">
                                                    <h6 class="priority-name">{{ $item['nama_mitra'] }}</h6>
                                                    <p class="priority-subtitle">
                                                        {{ $item['kontak'] }} ·
                                                        {{ $item['next_due_date']->format('Y-m-d') }}
                                                    </p>
                                                </div>
                                                <span class="badge bg-{{ $item['status_badge'] }}">
                                                    {{ $item['status_label'] }}
                                                </span>
                                            </div>

                                            <div class="priority-meta mb-3">
                                                <div class="priority-meta-card">
                                                    <span>Jatuh Tempo</span>
                                                    <strong>{{ $item['next_due_date']->format('Y-m-d') }}</strong>
                                                </div>
                                                <div class="priority-meta-card">
                                                    <span>Sisa Pokok</span>
                                                    <strong>Rp {{ number_format($item['pokok_sisa'], 0, ',', '.') }}</strong>
                                                </div>
                                                <div class="priority-meta-card">
                                                    <span>Notifikasi</span>
                                                    <strong>{{ $item['notification_status'] }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                    <p class="section-note">
                                        Menampilkan prioritas yang paling mendesak tanpa memenuhi halaman dashboard.
                                    </p>
                                    <a href="{{ route('notif.index') }}" class="btn btn-sm btn-outline-primary">
                                        Buka Notifikasi
                                    </a>
                                </div>

                                @if ($priorityItems->hasPages())
                                    <div class="section-pagination">
                                        {{ $priorityItems->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-5 mb-4">
                    <div class="card surface-card">
                        <div class="card-body">
                            <div class="section-heading">
                                <div>
                                    <h5>Pembayaran Terbaru</h5>
                                    <p class="section-caption">Aktivitas pembayaran terakhir yang masuk ke sistem.</p>
                                </div>
                                <span class="section-chip">
                                    4 transaksi per halaman
                                </span>
                            </div>

                            @if ($recentPayments->count() === 0)
                                <div class="empty-state">
                                    Belum ada aktivitas pembayaran terbaru.
                                </div>
                            @else
                                <div class="table-responsive table-shell">
                                    <table class="table activity-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Mitra</th>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recentPayments as $payment)
                                                <tr>
                                                    <td>{{ $payment->peminjaman?->nama_mitra ?? '-' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($payment->tanggal_pembayaran)->format('Y-m-d') }}</td>
                                                    <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <p class="section-note">
                                    Ringkasan ini hanya untuk aktivitas terbaru. Riwayat lengkap tetap ada di menu pembayaran.
                                </p>

                                @if ($recentPayments->hasPages())
                                    <div class="section-pagination">
                                        {{ $recentPayments->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-6 mb-4">
                    <div class="card surface-card">
                        <div class="card-body">
                            <div class="section-heading">
                                <div>
                                    <h5>Jatuh Tempo 30 Hari</h5>
                                    <p class="section-caption">Jadwal terdekat untuk persiapan reminder dan follow-up.</p>
                                </div>
                            </div>

                            @if ($upcomingItems->isEmpty())
                                <div class="empty-state">
                                    Tidak ada pinjaman dengan jatuh tempo dalam 30 hari ke depan.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table activity-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Mitra</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Sisa Pokok</th>
                                                <th class="action-col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($upcomingItems as $item)
                                                <tr>
                                                    <td>{{ $item['nama_mitra'] }}</td>
                                                    <td>{{ $item['next_due_date']->format('Y-m-d') }}</td>
                                                    <td>Rp {{ number_format($item['pokok_sisa'], 0, ',', '.') }}</td>
                                                    <td class="action-col">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-toggle="modal"
                                                            data-target="#upcomingDetailModal-{{ $item['id'] }}">
                                                            Detail
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($upcomingItems as $item)
                                    <div class="modal fade" id="upcomingDetailModal-{{ $item['id'] }}" tabindex="-1"
                                        role="dialog" aria-labelledby="upcomingDetailLabel-{{ $item['id'] }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div>
                                                        <h5 class="modal-title mb-1"
                                                            id="upcomingDetailLabel-{{ $item['id'] }}">
                                                            Detail Pinjaman Jatuh Tempo
                                                        </h5>
                                                        <small>{{ $item['nama_mitra'] }} · {{ $item['kontak'] }}</small>
                                                    </div>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="detail-highlight">
                                                        <h6>{{ $item['nama_mitra'] }}</h6>
                                                        <p>
                                                            Nomor Mitra: {{ $item['nomor_mitra'] ?: '-' }} |
                                                            Jatuh tempo berikutnya:
                                                            {{ $item['next_due_date']->format('Y-m-d') }} |
                                                            {{ $item['status_label'] }}
                                                        </p>
                                                    </div>

                                                    <div class="detail-grid">
                                                        <div class="detail-card">
                                                            <span>Jumlah Pinjaman</span>
                                                            <strong>Rp
                                                                {{ number_format($item['pokok_pinjaman_awal'], 0, ',', '.') }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Sisa Pinjaman</span>
                                                            <strong>Rp
                                                                {{ number_format($item['pokok_sisa'], 0, ',', '.') }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Cicilan</span>
                                                            <strong>
                                                                @if ($item['total_installments'] > 0)
                                                                    Ke-{{ $item['current_installment'] }} dari
                                                                    {{ $item['total_installments'] }}
                                                                @else
                                                                    Belum tersedia
                                                                @endif
                                                            </strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Pembayaran Tercatat</span>
                                                            <strong>{{ $item['completed_installments'] }} kali</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Tanggal Peminjaman</span>
                                                            <strong>{{ optional($item['tgl_peminjaman'])->format('Y-m-d') }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Pembayaran Terakhir</span>
                                                            <strong>
                                                                @if ($item['latest_payment_date'])
                                                                    {{ \Carbon\Carbon::parse($item['latest_payment_date'])->format('Y-m-d') }}
                                                                @else
                                                                    Belum ada pembayaran
                                                                @endif
                                                            </strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Nominal Pembayaran Terakhir</span>
                                                            <strong>
                                                                @if (! is_null($item['latest_payment_amount']))
                                                                    Rp
                                                                    {{ number_format($item['latest_payment_amount'], 0, ',', '.') }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Bunga</span>
                                                            <strong>{{ number_format((float) $item['bunga_persen'], 2, ',', '.') }}%</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Kualitas Kredit</span>
                                                            <strong>{{ $item['kualitas_kredit'] ?: 'Tidak Diketahui' }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Status Notifikasi</span>
                                                            <strong>{{ $item['notification_status'] }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Sisa Angsuran</span>
                                                            <strong>{{ $item['remaining_installments'] }} bulan</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Hari Menuju Jatuh Tempo</span>
                                                            <strong>{{ max($item['days_remaining'], 0) }} hari</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 mb-4">
                    <div class="card surface-card">
                        <div class="card-body">
                            <div class="section-heading">
                                <div>
                                    <h5>Pinjaman Terlambat</h5>
                                    <p class="section-caption">Daftar yang perlu prioritas penagihan atau tindak lanjut admin.</p>
                                </div>
                            </div>

                            @if ($overdueItems->isEmpty())
                                <div class="empty-state">
                                    Tidak ada pinjaman yang terlambat saat ini.
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table activity-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Mitra</th>
                                                <th>Terlambat</th>
                                                <th>Notifikasi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($overdueItems as $item)
                                                <tr>
                                                    <td>{{ $item['nama_mitra'] }}</td>
                                                    <td>{{ abs($item['days_remaining']) }} hari</td>
                                                    <td>{{ $item['notification_status'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials._footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartCanvas = document.getElementById('kualitasKreditChart');

            if (!chartCanvas) {
                return;
            }

            const labels = @json($chartData->keys()->values());
            const values = @json($chartData->values()->values());

            new Chart(chartCanvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Jumlah Pinjaman',
                        data: values,
                        backgroundColor: ['#1f6f50', '#f4b400', '#2980b9', '#d64550', '#8892a0'],
                        borderRadius: 10,
                        borderSkipped: false,
                        maxBarThickness: 48
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: 'rgba(20, 30, 58, 0.08)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
