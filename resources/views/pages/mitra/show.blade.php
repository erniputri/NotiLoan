@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Detail Mitra</p>
                <h3 class="page-title">{{ $mitra->nama_mitra }}</h3>
                <p class="page-copy">
                    Halaman ini merangkum identitas mitra, seluruh riwayat pinjaman, dan semua pembayaran yang pernah
                    dilakukan.
                </p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Ringkasan Mitra</h4>
                            <p>Gunakan halaman ini untuk melihat performa pinjaman per mitra secara menyeluruh.</p>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('mitra.index') }}" class="btn btn-outline-secondary">Kembali</a>
                            <a href="{{ route('mitra.edit', $mitra->id) }}" class="btn btn-primary btn-action">Edit Mitra</a>
                        </div>
                    </div>

                    <div class="summary-grid">
                        <div class="summary-tile">
                            <span>Total Pinjaman</span>
                            <strong>Rp {{ number_format($totalPinjaman, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-tile">
                            <span>Total Terbayar</span>
                            <strong>Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-tile">
                            <span>Sisa Outstanding</span>
                            <strong>Rp {{ number_format($totalSisa, 0, ',', '.') }}</strong>
                        </div>
                        <div class="summary-tile">
                            <span>Jumlah Pinjaman</span>
                            <strong>{{ $mitra->peminjaman_count }} data</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Profil Mitra</h4>
                            <p>Data identitas ini akan ikut tersinkron ke pinjaman yang terhubung dengan mitra ini.</p>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <span>Nomor Mitra</span>
                            <div class="detail-value">{{ $mitra->nomor_mitra ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Nama Mitra</span>
                            <div class="detail-value">{{ $mitra->nama_mitra }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Kontak</span>
                            <div class="detail-value">{{ $mitra->kontak ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Virtual Account</span>
                            <div class="detail-value">{{ $mitra->formatted_virtual_account ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Kabupaten</span>
                            <div class="detail-value">{{ $mitra->kabupaten ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Sektor</span>
                            <div class="detail-value">{{ $mitra->sektor ?: '-' }}</div>
                        </div>
                        <div class="detail-item is-full">
                            <span>Alamat</span>
                            <p>{{ $mitra->alamat ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Riwayat Pinjaman</h4>
                            <p>Seluruh pinjaman mitra ditampilkan agar progres setiap pinjaman mudah dilacak.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered history-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Peminjaman</th>
                                    <th>Pokok Pinjaman</th>
                                    <th>Sisa Pinjaman</th>
                                    <th>Sisa Bulan</th>
                                    <th>Kualitas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPinjaman as $loan)
                                    <tr>
                                        <td>{{ $loan->formatted_tgl_peminjaman ?: '-' }}</td>
                                        <td>{{ $loan->formatted_pokok_pinjaman_awal }}</td>
                                        <td>{{ $loan->formatted_pokok_sisa }}</td>
                                        <td>{{ $loan->formatted_lama_angsuran_bulan }}</td>
                                        <td>
                                            <span class="badge bg-{{ $loan->kualitas_kredit_class }}">
                                                {{ $loan->kualitas_kredit_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('data.show', $loan->id) }}" class="btn btn-sm btn-info">
                                                Detail Pinjaman
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada riwayat pinjaman untuk mitra ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($riwayatPinjaman->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $riwayatPinjaman->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Riwayat Pembayaran</h4>
                            <p>Semua pembayaran dari seluruh pinjaman mitra terkumpul dalam satu tabel.</p>
                        </div>
                        <a href="{{ route('mitra.payments.print', $mitra->id) }}" target="_blank"
                            class="btn btn-outline-primary">
                            Cetak / Simpan PDF
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered history-table">
                            <thead>
                                <tr>
                                    <th>Tanggal Bayar</th>
                                    <th>Pinjaman</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPembayaran as $payment)
                                    <tr>
                                        <td>{{ $payment->formatted_tanggal_pembayaran ?: '-' }}</td>
                                        <td>{{ $payment->peminjaman?->formatted_tgl_peminjaman ?: '-' }}</td>
                                        <td>{{ $payment->formatted_jumlah_bayar }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->payment_status_class }}">
                                                {{ $payment->payment_status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($payment->bukti_pembayaran_url)
                                                <a href="{{ $payment->bukti_pembayaran_url }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary">
                                                    Lihat Bukti
                                                </a>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Belum ada riwayat pembayaran untuk mitra ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($riwayatPembayaran->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $riwayatPembayaran->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Riwayat Notifikasi</h4>
                            <p>Riwayat ini menampilkan seluruh percobaan pengiriman notifikasi ke mitra, baik otomatis maupun manual.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered history-table">
                            <thead>
                                <tr>
                                    <th>Waktu Kirim</th>
                                    <th>Jenis Notifikasi</th>
                                    <th>Pinjaman</th>
                                    <th>Kontak</th>
                                    <th>Status</th>
                                    <th>Pesan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatNotifikasi as $attempt)
                                    <tr>
                                        <td>{{ $attempt->formatted_attempted_at ?: '-' }}</td>
                                        <td>{{ $attempt->trigger_type_label }}</td>
                                        <td>{{ $attempt->peminjaman?->formatted_tgl_peminjaman ?: '-' }}</td>
                                        <td>{{ $attempt->kontak ?: '-' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $attempt->send_status_class }}">
                                                {{ $attempt->send_status_label }}
                                            </span>
                                            @if ($attempt->response_code)
                                                <small class="field-hint d-block">{{ $attempt->response_code }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="table-message">
                                                {{ $attempt->message }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Belum ada riwayat notifikasi untuk mitra ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($riwayatNotifikasi->hasPages())
                        <div class="d-flex justify-content-end mt-3">
                            {{ $riwayatNotifikasi->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
