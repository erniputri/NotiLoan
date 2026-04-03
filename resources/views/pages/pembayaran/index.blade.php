@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper list-page">
            <style>
                .list-page .page-hero {
                    background: linear-gradient(135deg, #123524 0%, #1f6f50 62%, #d7efe4 100%);
                    border-radius: 24px;
                    padding: 26px 28px;
                    color: #fff;
                    box-shadow: 0 18px 36px rgba(18, 53, 36, 0.16);
                    margin-bottom: 22px;
                }

                .list-page .page-kicker {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.16em;
                    opacity: 0.8;
                    margin-bottom: 8px;
                }

                .list-page .page-title {
                    font-size: 30px;
                    font-weight: 700;
                    margin-bottom: 8px;
                }

                .list-page .page-copy {
                    margin-bottom: 0;
                    color: rgba(255, 255, 255, 0.82);
                    max-width: 680px;
                }

                .list-page .hero-stat-grid {
                    display: grid;
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                    gap: 12px;
                }

                .list-page .hero-stat {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.12);
                    border-radius: 18px;
                    padding: 14px 16px;
                }

                .list-page .hero-stat span {
                    display: block;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                    color: rgba(255, 255, 255, 0.72);
                    margin-bottom: 4px;
                }

                .list-page .hero-stat strong {
                    font-size: 24px;
                    font-weight: 700;
                }

                .list-page .surface-card {
                    background: #fff;
                    border: 1px solid #dcebe1;
                    border-radius: 22px;
                    box-shadow: 0 14px 30px rgba(18, 53, 36, 0.07);
                    margin-bottom: 20px;
                }

                .list-page .surface-card .card-body {
                    padding: 22px;
                }

                .list-page .section-heading {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 16px;
                    margin-bottom: 18px;
                }

                .list-page .section-heading h4 {
                    margin-bottom: 6px;
                    font-weight: 700;
                    color: #203126;
                }

                .list-page .section-caption {
                    margin-bottom: 0;
                    color: #6f7f74;
                    font-size: 14px;
                }

                .list-page .toolbar-grid {
                    display: grid;
                    grid-template-columns: minmax(240px, 1fr) auto;
                    gap: 16px;
                    align-items: end;
                }

                .list-page .search-box {
                    position: relative;
                }

                .list-page .search-box i {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #6f7f74;
                }

                .list-page .search-box .form-control {
                    padding-left: 42px;
                    height: 44px;
                    border-radius: 14px;
                }

                .list-page .surface-note {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 12px;
                    border-radius: 999px;
                    background: #eff8f2;
                    color: #1f6f50;
                    font-size: 13px;
                    font-weight: 600;
                }

                .list-page .table-shell {
                    border: 1px solid #e1eee6;
                    border-radius: 18px;
                    overflow: hidden;
                }

                .list-page .table {
                    margin-bottom: 0;
                }

                .list-page .table thead th {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.06em;
                    border-bottom: 1px solid #e1eee6;
                }

                .list-page .table td {
                    vertical-align: middle;
                    border-color: #edf5f0;
                    padding-top: 16px;
                    padding-bottom: 16px;
                }

                .list-page .name-cell strong {
                    display: block;
                    color: #203126;
                    font-size: 15px;
                }

                .list-page .name-cell small,
                .list-page .muted-meta {
                    color: #76877c;
                    font-size: 13px;
                }

                .list-page .money-pill {
                    display: inline-flex;
                    align-items: center;
                    padding: 7px 12px;
                    border-radius: 999px;
                    background: #f3faf5;
                    color: #1f6f50;
                    font-weight: 700;
                }

                .list-page .status-pill {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 110px;
                    padding: 8px 12px;
                    border-radius: 999px;
                    font-size: 12px;
                    font-weight: 700;
                }

                .list-page .status-pill.success {
                    background: #dff3e6;
                    color: #1f6f50;
                }

                .list-page .status-pill.warning {
                    background: #fff3d6;
                    color: #9a6a00;
                }

                .list-page .status-pill.danger {
                    background: #fbe0e3;
                    color: #b63a4b;
                }

                .list-page .action-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    justify-content: center;
                }

                .list-page .empty-state {
                    padding: 40px 24px;
                    text-align: center;
                    background: #fbfefd;
                    color: #708077;
                }

                .list-page .empty-state i {
                    font-size: 36px;
                    color: #8bcfb0;
                    margin-bottom: 12px;
                    display: block;
                }

                .list-page .footer-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 12px;
                    margin-top: 18px;
                    flex-wrap: wrap;
                }

                @media (max-width: 991.98px) {
                    .list-page .hero-stat-grid,
                    .list-page .toolbar-grid {
                        grid-template-columns: 1fr;
                    }
                }
            </style>

            <div class="page-hero">
                <div class="row align-items-center">
                    <div class="col-xl-7 mb-4 mb-xl-0">
                        <p class="page-kicker">Pembayaran</p>
                        <h3 class="page-title">Pantau transaksi pembayaran pinjaman</h3>
                        <p class="page-copy">
                            Halaman ini membantu admin melihat pembayaran terbaru, memeriksa status pelunasan, dan
                            masuk ke detail transaksi dengan lebih cepat.
                        </p>
                    </div>

                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    <div class="section-heading">
                        <div>
                            <h4>Filter dan Aksi</h4>
                            <p class="section-caption">Cari transaksi berdasarkan nama mitra dan lanjutkan ke pencatatan pembayaran baru.</p>
                        </div>
                        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary btn-action">
                            <i class="mdi mdi-plus-circle-outline"></i>
                            Tambah Pembayaran
                        </a>
                    </div>

                    <div class="toolbar-grid">
                        <form method="GET" action="{{ route('pembayaran.index') }}">
                            <label class="muted-meta mb-2 d-block">Cari transaksi</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="search-box flex-grow-1">
                                    <i class="mdi mdi-magnify"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari nama mitra..." class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-magnify"></i>
                                    Cari
                                </button>
                                @if (request('search'))
                                    <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary btn-action">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>

                        <span class="surface-note">
                            <i class="mdi mdi-information-outline"></i>
                            Status dibaca dari sisa pokok pinjaman saat ini
                        </span>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    <div class="section-heading">
                        <div>
                            <h4>Daftar Pembayaran</h4>
                            <p class="section-caption">Gunakan tabel ini untuk melihat progres pembayaran setiap mitra secara cepat.</p>
                        </div>
                    </div>

                    <div class="table-responsive table-shell">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mitra</th>
                                    <th>Jumlah Pinjaman</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Sisa Pokok</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pembayaran as $item)
                                    @php
                                        $status = 'Belum Bayar';
                                        $statusClass = 'danger';

                                        if ($item->peminjaman->pokok_sisa == 0) {
                                            $status = 'Lunas';
                                            $statusClass = 'success';
                                        } elseif ($item->peminjaman->pokok_sisa < $item->peminjaman->pokok_pinjaman_awal) {
                                            $status = 'Mencicil';
                                            $statusClass = 'warning';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $item->peminjaman->nama_mitra }}</strong>
                                                <small>Pembayaran ID #{{ $item->id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="money-pill">Rp {{ number_format($item->peminjaman->pokok_pinjaman_awal, 0, ',', '.') }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="money-pill">Rp {{ number_format($item->jumlah_bayar, 0, ',', '.') }}</span>
                                        </td>
                                        <td>Rp {{ number_format($item->peminjaman->pokok_sisa, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="status-pill {{ $statusClass }}">{{ $status }}</span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('pembayaran.show', $item->id) }}" class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('pembayaran.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('pembayaran.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Yakin hapus pembayaran ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger" type="submit">
                                                        <i class="mdi mdi-delete"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">
                                            <i class="mdi mdi-cash-remove"></i>
                                            <strong class="d-block mb-1">Belum ada data pembayaran</strong>
                                            <span>Tambahkan pembayaran baru atau ubah kata kunci pencarian.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="footer-row">
                        <p class="muted-meta mb-0">Menampilkan {{ $pembayaran->count() }} dari {{ $pembayaran->total() }} transaksi pembayaran.</p>
                        <div>
                            {{ $pembayaran->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
