@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper list-page">
            <div class="page-hero">
                <div class="row align-items-center">
                    <div class="col-xl-7 mb-4 mb-xl-0">
                        <p class="page-kicker">Data Mitra</p>
                        <h3 class="page-title">Kelola identitas dan riwayat mitra</h3>
                        <p class="page-copy">
                            Data mitra terbentuk otomatis saat pinjaman ditambahkan. Halaman ini dipakai untuk melihat
                            ringkasan, riwayat pinjaman, dan memperbarui profil mitra.
                        </p>
                    </div>
                    <div class="col-xl-5">
                        <div class="hero-stat-grid">
                            <div class="hero-stat">
                                <span>Total Mitra</span>
                                <strong>{{ $totalMitra }}</strong>
                            </div>
                            <div class="hero-stat">
                                <span>Mitra Aktif</span>
                                <strong>{{ $mitraAktif }}</strong>
                            </div>
                            <div class="hero-stat">
                                <span>Outstanding</span>
                                <strong>Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    <div class="section-heading">
                        <div>
                            <h4>Daftar Mitra</h4>
                            <p class="section-caption">Cari mitra dan buka detail untuk melihat seluruh riwayat pinjaman dan pembayaran.</p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('mitra.index') }}" class="mb-4">
                        <div class="toolbar-grid">
                            <div class="search-box">
                                <i class="mdi mdi-magnify"></i>
                                <input type="text" name="search" value="{{ $search }}"
                                    placeholder="Cari nama, nomor mitra, kontak, atau kabupaten..."
                                    class="form-control">
                            </div>
                            <div>
                                <select name="loan_status" class="form-control">
                                    <option value="">Semua Status Pinjaman</option>
                                    <option value="aktif" {{ $loanStatus === 'aktif' ? 'selected' : '' }}>
                                        Mitra Dengan Pinjaman Aktif
                                    </option>
                                    <option value="lunas" {{ $loanStatus === 'lunas' ? 'selected' : '' }}>
                                        Mitra Dengan Pinjaman Lunas
                                    </option>
                                </select>
                            </div>
                            <div class="stack-actions">
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-magnify"></i>
                                    Cari
                                </button>
                                @if ($search || $loanStatus)
                                    <a href="{{ route('mitra.index') }}" class="btn btn-outline-secondary btn-action">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive table-shell">
                        <table class="table">
                            <thead>
                                 <tr>
                                     <th>Mitra</th>
                                     <th>Kontak</th>
                                     <th>Status</th>
                                     <th>Total Pinjaman</th>
                                     <th>Sisa Pinjaman</th>
                                     <th>Jumlah Pinjaman</th>
                                     <th class="text-center">Aksi</th>
                                 </tr>
                            </thead>
                            <tbody>
                                @forelse ($mitras as $item)
                                    <tr>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $item->nama_mitra }}</strong>
                                                <small>{{ $item->nomor_mitra ?: 'Nomor mitra belum tersedia' }}</small>
                                            </div>
                                        </td>
                                         <td>
                                             <div class="name-cell">
                                                 <strong>{{ $item->kontak ?: '-' }}</strong>
                                                 <small>{{ $item->kabupaten ?: 'Kabupaten belum diisi' }}</small>
                                             </div>
                                         </td>
                                         <td>
                                             <span class="status-pill {{ $item->active_loan_count > 0 ? 'warning' : 'success' }}">
                                                 {{ $item->active_loan_count > 0 ? 'Aktif' : 'Lunas' }}
                                             </span>
                                         </td>
                                         <td><span class="money-pill">{{ $item->formatted_total_pinjaman }}</span></td>
                                         <td><span class="money-pill">{{ $item->formatted_total_sisa }}</span></td>
                                         <td>
                                             <span class="status-pill {{ $item->active_loan_count > 0 ? 'warning' : 'success' }}">
                                                 {{ $item->peminjaman_count }} pinjaman
                                            </span>
                                        </td>
                                        <td class="action-cell">
                                            <div class="action-group">
                                                <a href="{{ route('mitra.show', $item->id) }}" class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('mitra.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                 @empty
                                     <tr>
                                         <td colspan="7" class="empty-state">
                                             <i class="mdi mdi-account-search"></i>
                                             <strong class="d-block mb-1">Data mitra belum ditemukan</strong>
                                             <span>Tambah data pinjaman baru agar data mitra terbentuk otomatis di halaman ini.</span>
                                         </td>
                                     </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="footer-row">
                        <p class="muted-meta mb-0">Gunakan detail mitra untuk melihat seluruh riwayat pinjaman dan pembayaran dalam satu halaman.</p>
                        <div>
                            {{ $mitras->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
