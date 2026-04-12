@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper dashboard-page">

            <div class="row mb-4">
                <div class="col-12">
                    {{-- Panel hero merangkum kondisi operasional harian sebelum user masuk ke panel-panel detail. --}}
                    <div class="hero-panel">
                        <div class="row align-items-center">
                            <div class="col-xl-7 mb-4 mb-xl-0">
                                <p class="hero-kicker">Dashboard TJSL PTPN IV REGIONAL III</p>
                                <h3 class="hero-title">Halo, {{ Auth::user()->name }}</h3>
                                <p class="hero-copy"></p>
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
                    {{-- Kartu KPI ini adalah pintu baca tercepat untuk kondisi inti sistem. --}}
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="metric-topline">
                                <span class="metric-label">Total Pinjaman</span>
                                <span class="metric-icon metric-icon--primary">
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
                                <span class="metric-icon metric-icon--warning">
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
                                <span class="metric-icon metric-icon--success">
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
                                <span class="metric-icon metric-icon--accent">
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
                            {{-- Chart membantu admin melihat pola kualitas kredit tanpa harus membaca tabel panjang. --}}
                            <div class="section-heading">
                                <div>
                                    <h4>Distribusi Kualitas Kredit</h4>
                                    <p class="section-caption">Komposisi kualitas kredit berdasarkan pinjaman yang dibuat pada periode {{ strtolower($chartPeriodLabel) }}.</p>
                                </div>
                                <div class="period-switch">
                                    <a href="{{ $chartPeriodLinks['daily'] }}"
                                        class="period-switch-link {{ $chartPeriod === 'daily' ? 'is-active' : '' }}">
                                        Daily
                                    </a>
                                    <a href="{{ $chartPeriodLinks['weekly'] }}"
                                        class="period-switch-link {{ $chartPeriod === 'weekly' ? 'is-active' : '' }}">
                                        Weekly
                                    </a>
                                    <a href="{{ $chartPeriodLinks['monthly'] }}"
                                        class="period-switch-link {{ $chartPeriod === 'monthly' ? 'is-active' : '' }}">
                                        Monthly
                                    </a>
                                </div>
                            </div>
                            <div class="chart-shell">
                                <canvas
                                    id="kualitasKreditChart"
                                    data-chart-labels='@json($chartData->keys()->values())'
                                    data-chart-values='@json($chartData->values()->values())'></canvas>
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
                            {{-- Daftar prioritas dibatasi agar dashboard tetap fokus pada kasus yang paling mendesak. --}}
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
                                                        {{ $item['kontak'] }} |
                                                        {{ $item['formatted_next_due_date'] }}
                                                    </p>
                                                </div>
                                                <span class="badge bg-{{ $item['status_badge'] }}">
                                                    {{ $item['status_label'] }}
                                                </span>
                                            </div>

                                            <div class="priority-meta mb-3">
                                                <div class="priority-meta-card">
                                                    <span>Jatuh Tempo</span>
                                                    <strong>{{ $item['formatted_next_due_date'] }}</strong>
                                                </div>
                                                <div class="priority-meta-card">
                                                    <span>Sisa Pokok</span>
                                                    <strong>{{ $item['formatted_pokok_sisa'] }}</strong>
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
                            {{-- Tabel ini hanya memberi ringkasan transaksi terbaru, bukan histori pembayaran lengkap. --}}
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
                                                    <td>{{ $payment->formatted_tanggal_pembayaran ?? '-' }}</td>
                                                    <td>{{ $payment->formatted_jumlah_bayar }}</td>
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
                            {{-- Section ini dipakai untuk persiapan reminder sebelum pinjaman masuk fase terlambat. --}}
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
                                                    <td>{{ $item['formatted_next_due_date'] }}</td>
                                                    <td>{{ $item['formatted_pokok_sisa'] }}</td>
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

                                {{-- Modal per item dipakai agar admin bisa membuka detail cepat tanpa meninggalkan dashboard. --}}
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
                                                        <small>{{ $item['nama_mitra'] }} | {{ $item['kontak'] }}</small>
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
                                                            {{ $item['formatted_next_due_date'] }} |
                                                            {{ $item['status_label'] }}
                                                        </p>
                                                    </div>

                                                    <div class="detail-grid">
                                                        <div class="detail-card">
                                                            <span>Jumlah Pinjaman</span>
                                                            <strong>{{ $item['formatted_pokok_pinjaman_awal'] }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Sisa Pinjaman</span>
                                                            <strong>{{ $item['formatted_pokok_sisa'] }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Cicilan</span>
                                                            <strong>{{ $item['installment_label'] }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Pembayaran Tercatat</span>
                                                            <strong>{{ $item['completed_installments'] }} kali</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Tanggal Peminjaman</span>
                                                            <strong>{{ $item['formatted_tgl_peminjaman'] }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Pembayaran Terakhir</span>
                                                            <strong>{{ $item['formatted_latest_payment_date'] === '-' ? 'Belum ada pembayaran' : $item['formatted_latest_payment_date'] }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Nominal Pembayaran Terakhir</span>
                                                            <strong>{{ $item['formatted_latest_payment_amount'] }}</strong>
                                                        </div>
                                                        <div class="detail-card">
                                                            <span>Bunga</span>
                                                            <strong>{{ $item['formatted_bunga_persen'] }}</strong>
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
                                                            <strong>{{ $item['days_remaining'] < 0 ? '0 hari' : $item['formatted_days_remaining'] }}</strong>
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
                            {{-- Pinjaman terlambat dipisahkan agar tindak lanjut penagihan tidak bercampur dengan reminder biasa. --}}
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
                                                    <td>{{ $item['formatted_days_remaining'] }}</td>
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
@endsection
