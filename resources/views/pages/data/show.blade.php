@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Detail Pinjaman</p>
                <h3 class="page-title">{{ $peminjaman->nama_mitra }}</h3>
                <p class="page-copy">Halaman ini merangkum informasi utama mitra, status pinjaman, serta atribut administrasi yang tersimpan di sistem.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Ringkasan Pinjaman</h4>
                            <p>Gunakan halaman ini sebelum melakukan edit, mencatat pembayaran, atau mengirim notifikasi.</p>
                        </div>
                        <a href="{{ route('data.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item"><span>Nama Mitra</span><div class="detail-value">{{ $peminjaman->nama_mitra }}</div></div>
                        <div class="detail-item"><span>Kontak</span><div class="detail-value">{{ $peminjaman->kontak }}</div></div>
                        <div class="detail-item is-full"><span>Alamat</span><p>{{ $peminjaman->alamat ?: '-' }}</p></div>
                        <div class="detail-item"><span>Kabupaten</span><div class="detail-value">{{ $peminjaman->kabupaten ?: '-' }}</div></div>
                        <div class="detail-item"><span>Sektor</span><div class="detail-value">{{ $peminjaman->sektor ?: '-' }}</div></div>
                        <div class="detail-item"><span>Pokok Pinjaman</span><div class="detail-value">Rp {{ number_format($peminjaman->pokok_pinjaman_awal, 0, ',', '.') }}</div></div>
                        <div class="detail-item"><span>Sisa Pinjaman</span><div class="detail-value">Rp {{ number_format($peminjaman->pokok_sisa, 0, ',', '.') }}</div></div>
                        <div class="detail-item"><span>Sisa Bulan</span><div class="detail-value">{{ number_format($peminjaman->lama_angsuran_bulan) }} bulan</div></div>
                        <div class="detail-item">
                            <span>Bunga (%)</span>
                            <div class="detail-value">
                                {{ rtrim(rtrim(number_format($peminjaman->bunga_persen, 2), '0'), '.') }}%
                                <small class="field-hint d-block">Administrasi awal: Rp {{ number_format($peminjaman->administrasi_awal, 0, ',', '.') }}</small>
                            </div>
                        </div>
                        <div class="detail-item"><span>Tanggal Peminjaman</span><div class="detail-value">{{ optional($peminjaman->tgl_peminjaman)->format('Y-m-d') }}</div></div>
                        <div class="detail-item"><span>Jatuh Tempo</span><div class="detail-value">{{ optional($peminjaman->tgl_jatuh_tempo)->format('Y-m-d') }}</div></div>
                        <div class="detail-item">
                            <span>Kualitas Kredit</span>
                            <div class="detail-value">
                                @if ($peminjaman->kualitas_kredit == 'Lancar')
                                    <span class="badge bg-success">Lancar</span>
                                @elseif ($peminjaman->kualitas_kredit == 'Kurang Lancar')
                                    <span class="badge bg-warning">Kurang Lancar</span>
                                @elseif ($peminjaman->kualitas_kredit == 'Ragu-ragu')
                                    <span class="badge bg-info">Ragu-ragu</span>
                                @elseif ($peminjaman->kualitas_kredit == 'Macet')
                                    <span class="badge bg-danger">Macet</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Diketahui</span>
                                @endif
                            </div>
                        </div>
                        <div class="detail-item is-full"><span>Jaminan</span><p>{{ $peminjaman->jaminan ?: '-' }}</p></div>
                    </div>

                    <div class="detail-actions">
                        <a href="{{ route('data.index') }}" class="btn btn-outline-secondary">Kembali ke Data</a>
                        <a href="{{ route('data.edit.step1', $peminjaman->id) }}" class="btn btn-primary btn-action">Edit Data Ini</a>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
