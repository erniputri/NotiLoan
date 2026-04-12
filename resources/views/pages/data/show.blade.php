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
                        <div class="detail-item"><span>Pokok Pinjaman</span><div class="detail-value">{{ $peminjaman->formatted_pokok_pinjaman_awal }}</div></div>
                        <div class="detail-item"><span>Sisa Pinjaman</span><div class="detail-value">{{ $peminjaman->formatted_pokok_sisa }}</div></div>
                        <div class="detail-item"><span>Sisa Bulan</span><div class="detail-value">{{ $peminjaman->formatted_lama_angsuran_bulan }}</div></div>
                        <div class="detail-item">
                            <span>Bunga (%)</span>
                            <div class="detail-value">
                                {{ $peminjaman->formatted_bunga_persen }}
                                <small class="field-hint d-block">Administrasi awal: {{ $peminjaman->formatted_administrasi_awal }}</small>
                            </div>
                        </div>
                        <div class="detail-item"><span>Tanggal Peminjaman</span><div class="detail-value">{{ $peminjaman->formatted_tgl_peminjaman ?? '-' }}</div></div>
                        <div class="detail-item"><span>Jatuh Tempo</span><div class="detail-value">{{ $peminjaman->formatted_tgl_jatuh_tempo ?? '-' }}</div></div>
                        <div class="detail-item">
                            <span>Kualitas Kredit</span>
                            <div class="detail-value">
                                <span class="badge bg-{{ $peminjaman->kualitas_kredit_class }}">{{ $peminjaman->kualitas_kredit_label }}</span>
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
