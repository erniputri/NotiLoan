@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper list-page list-page--payment">
            

            {{-- Bagian pembuka halaman untuk memberi konteks cepat tentang fungsi halaman pembayaran. --}}
            <div class="page-hero">
                <div class="row align-items-center">
                    <div class="col-xl-7 mb-4 mb-xl-0">
                        <p class="page-kicker">Pembayaran</p>
                        <h3 class="page-title">Pantau Pembayaran Pinjaman</h3>
                        <p class="page-copy">
                            Lihat transaksi, cek status pelunasan, dan kelola pembayaran dengan cepat.
                        </p>
                    </div>

                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    {{-- Area ini dipakai untuk dua kebutuhan utama: mencari transaksi lama dan menambah transaksi baru. --}}
                    <div class="section-heading">
                        <div>
                            <h4>Filter dan Aksi</h4>
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

                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    <div class="section-heading">
                        <div>
                            <h4>Daftar Pembayaran</h4>
                        </div>
                    </div>

                    <div class="table-responsive table-shell">
                        <table class="table">
                            <thead>
                                <tr>
                                    {{-- Kolom tabel disusun mengikuti alur baca admin: siapa mitranya, berapa nominalnya, lalu aksi yang bisa dilakukan. --}}
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
                                    <tr>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $item->peminjaman->nama_mitra }}</strong>
                                                <small>Pembayaran ID #{{ $item->id }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="money-pill">{{ $item->peminjaman->formatted_pokok_pinjaman_awal }}</span>
                                        </td>
                                        <td>{{ $item->formatted_tanggal_pembayaran ?? '-' }}</td>
                                        <td>
                                            <span class="money-pill">{{ $item->formatted_jumlah_bayar }}</span>
                                        </td>
                                        <td>{{ $item->peminjaman->formatted_pokok_sisa }}</td>
                                        <td>
                                            <span class="status-pill {{ $item->payment_status_class }}">{{ $item->payment_status_label }}</span>
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
                                                    data-confirm-message="Yakin hapus pembayaran ini?">
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
                                        {{-- Empty state ini muncul saat belum ada transaksi atau hasil pencarian tidak menemukan data. --}}
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
                        {{-- Ringkasan ini membantu admin tahu berapa data yang sedang terlihat dibanding total transaksi. --}}
                        <p class="muted-meta mb-0">Menampilkan {{ $pembayaran->count() }} dari {{ $pembayaran->total() }} transaksi pembayaran.</p>
                        <div>
                            {{-- Pagination dipisah di footer agar navigasi halaman tetap konsisten dan mudah ditemukan. --}}
                            {{ $pembayaran->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
