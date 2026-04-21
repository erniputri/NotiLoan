@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Detail Pinjaman</p>
                <h3 class="page-title">{{ $peminjaman->nama_mitra }}</h3>
                <p class="page-copy">
                    Halaman ini fokus pada satu data pinjaman. Untuk profil mitra dan seluruh riwayat pinjaman, gunakan
                    halaman mitra.
                </p>
            </div>

            @if ($peminjaman->mitra)
                <div class="context-banner">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="NotiLoan">
                    <div>
                        <strong>Detail mitra dipisahkan ke halaman khusus</strong>
                        <p>
                            Profil mitra, seluruh riwayat pinjaman, dan riwayat pembayaran lengkap tersedia di halaman
                            mitra.
                            <a href="{{ route('mitra.show', $peminjaman->mitra->id) }}">Buka detail mitra</a>
                        </p>
                    </div>
                </div>
            @endif

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Ringkasan Pinjaman</h4>
                            <p>Gunakan informasi ini untuk meninjau progres pinjaman sebelum melakukan edit atau pembayaran.</p>
                        </div>
                        <a href="{{ route('data.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <div class="summary-grid">
                        <div class="summary-tile">
                            <span>Status Pinjaman</span>
                            <strong>{{ $peminjaman->loan_status_label }}</strong>
                        </div>
                        <div class="summary-tile">
                            <span>Sisa Pinjaman</span>
                            <strong>{{ $peminjaman->formatted_pokok_sisa }}</strong>
                        </div>
                        <div class="summary-tile">
                            <span>Angsuran Ke</span>
                            <strong>
                                {{ $angsuranKe > 0 ? $angsuranKe : '-' }}
                                @if ($perkiraanTotalAngsuran > 0)
                                    <small>/ {{ $perkiraanTotalAngsuran }}</small>
                                @endif
                            </strong>
                        </div>
                        <div class="summary-tile">
                            <span>Kualitas Kredit</span>
                            <strong>{{ $peminjaman->kualitas_kredit_label }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Detail Peminjaman</h4>
                            <p>Rincian pinjaman disajikan lengkap untuk kebutuhan analisis dan tindak lanjut.</p>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <span>Nomor Mitra</span>
                            <div class="detail-value">{{ $peminjaman->nomor_mitra ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Nama Mitra</span>
                            <div class="detail-value">{{ $peminjaman->nama_mitra }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Pokok Peminjaman</span>
                            <div class="detail-value">{{ $peminjaman->formatted_pokok_pinjaman_awal }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Total Terbayar</span>
                            <div class="detail-value">Rp {{ number_format($totalTerbayar, 0, ',', '.') }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Sisa Pinjaman</span>
                            <div class="detail-value">{{ $peminjaman->formatted_pokok_sisa }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Sisa Bulan</span>
                            <div class="detail-value">{{ $peminjaman->formatted_lama_angsuran_bulan }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Tanggal Peminjaman</span>
                            <div class="detail-value">{{ $peminjaman->formatted_tgl_peminjaman ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Jatuh Tempo</span>
                            <div class="detail-value">{{ $peminjaman->formatted_tgl_jatuh_tempo ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Angsuran Terakhir</span>
                            <div class="detail-value">
                                {{ $angsuranTerakhir?->formatted_tanggal_pembayaran ?: '-' }}
                                @if ($angsuranTerakhir)
                                    <small class="field-hint d-block">{{ $angsuranTerakhir->formatted_jumlah_bayar }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item">
                            <span>Jumlah Pembayaran Tercatat</span>
                            <div class="detail-value">{{ $totalPembayaran }} transaksi</div>
                        </div>
                        <div class="detail-item">
                            <span>Kualitas Peminjaman</span>
                            <div class="detail-value">
                                <span class="badge bg-{{ $peminjaman->kualitas_kredit_class }}">
                                    {{ $peminjaman->kualitas_kredit_label }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <span>Status Notifikasi</span>
                            <div class="detail-value">
                                <span class="badge bg-{{ $peminjaman->notification_status_class }}">
                                    {{ $peminjaman->notification_status_label }}
                                </span>
                            </div>
                        </div>
                        <div class="detail-item">
                            <span>Bunga</span>
                            <div class="detail-value">{{ $peminjaman->formatted_bunga_persen }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Administrasi Awal</span>
                            <div class="detail-value">{{ $peminjaman->formatted_administrasi_awal }}</div>
                        </div>
                        <div class="detail-item">
                            <span>No. Surat Perjanjian</span>
                            <div class="detail-value">{{ $peminjaman->no_surat_perjanjian ?: '-' }}</div>
                        </div>
                        <div class="detail-item">
                            <span>Virtual Account</span>
                            <div class="detail-value">{{ $peminjaman->formatted_virtual_account ?: '-' }}</div>
                        </div>
                        <div class="detail-item is-full">
                            <span>Jaminan</span>
                            <p>{{ $peminjaman->jaminan ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="detail-actions">
                        <a href="{{ route('data.index') }}" class="btn btn-outline-secondary">Kembali ke Data</a>
                        <div class="d-flex gap-2 flex-wrap">
                            @if ($peminjaman->mitra)
                                <a href="{{ route('mitra.show', $peminjaman->mitra->id) }}" class="btn btn-outline-primary">
                                    Lihat Detail Mitra
                                </a>
                            @endif
                            <a href="{{ route('data.edit.step1', $peminjaman->id) }}" class="btn btn-primary btn-action">
                                Edit Data Ini
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
