@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Detail Pembayaran</p>
                <h3 class="page-title">{{ $pembayaran->peminjaman->nama_mitra }}</h3>
                <p class="page-copy">Ringkasan ini membantu admin meninjau transaksi pembayaran beserta dampaknya pada sisa pinjaman.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Ringkasan Pembayaran</h4>
                            <p>Gunakan halaman ini untuk memeriksa transaksi sebelum mengubah atau menghapus data pembayaran.</p>
                        </div>
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item"><span>Nama Mitra</span><div class="detail-value">{{ $pembayaran->peminjaman->nama_mitra }}</div></div>
                        <div class="detail-item"><span>Tanggal Pembayaran</span><div class="detail-value">{{ $pembayaran->formatted_tanggal_pembayaran ?? '-' }}</div></div>
                        <div class="detail-item"><span>Jumlah Bayar</span><div class="detail-value">{{ $pembayaran->formatted_jumlah_bayar }}</div></div>
                        <div class="detail-item"><span>Sisa Pinjaman</span><div class="detail-value">{{ $pembayaran->peminjaman->formatted_pokok_sisa }}</div></div>
                        <div class="detail-item is-full">
                            <span>Bukti Pembayaran</span>
                            @if ($pembayaran->bukti_pembayaran_url)
                                <a href="{{ $pembayaran->bukti_pembayaran_url }}" target="_blank" class="btn btn-info btn-action">
                                    Lihat Bukti Pembayaran
                                </a>
                            @else
                                <p>-</p>
                            @endif
                        </div>
                    </div>

                    <div class="detail-actions">
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Kembali ke Daftar</a>
                        <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-primary btn-action">Edit Pembayaran</a>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
