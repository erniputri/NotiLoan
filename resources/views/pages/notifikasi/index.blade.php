@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper list-page list-page--notif">
            

            {{-- Hero dipakai untuk menjelaskan bahwa halaman ini fokus pada monitoring dan pengiriman notifikasi. --}}
            <div class="page-hero">
                <div class="row align-items-center">
                    <div class="col-xl-7 mb-4 mb-xl-0">
                        <p class="page-kicker">Notifikasi</p>
                        <h3 class="page-title">Reminder & Notifikasi Mitra</h3>
                        <p class="page-copy">
                            Pantau status dan kirim notifikasi mitra dengan cepat dan terorganisir.
                        </p>
                    </div>
                    <div class="col-xl-5">
                        <div class="hero-stat-grid">
                            <div class="hero-stat">
                                <span>Total Data</span>
                                <strong>{{ $dataPeminjaman->total() }}</strong>
                            </div>
                            <div class="hero-stat">
                                <span>Total Pending</span>
                                <strong>{{ $pendingNotificationCount }}</strong>
                            </div>
                            <div class="hero-stat">
                                <span>Total Terkirim</span>
                                <strong>{{ $sentNotificationCount }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    {{-- Filter dipisahkan dari tabel agar pencarian penerima notifikasi lebih cepat dilakukan. --}}
                    <div class="section-heading">
                        <div>
                            <h4>Filter Data</h4>
                        </div>
                        <span class="surface-note">
                            <i class="mdi mdi-bell-outline"></i>
                            Kirim manual hanya muncul untuk mitra yang sudah jatuh tempo dan belum membayar
                        </span>
                    </div>

                    <div class="toolbar-grid">
                        <form method="GET" action="{{ route('notif.index') }}">
                            <label class="muted-meta mb-2 d-block">Cari penerima notifikasi</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="search-box flex-grow-1">
                                    <i class="mdi mdi-magnify"></i>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                        placeholder="Cari nama atau kontak...">
                                </div>
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-magnify"></i>
                                    Cari
                                </button>
                                @if ($search)
                                    <a href="{{ route('notif.index') }}" class="btn btn-outline-secondary btn-action">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>

                        <span class="surface-note">
                            <i class="mdi mdi-filter-outline"></i>
                            {{ $search ? 'Filter aktif: "'.$search.'"' : 'Menampilkan seluruh data yang tersedia' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    {{-- Tabel ini adalah area kerja utama untuk melihat status notifikasi dan melakukan kirim manual. --}}
                    <div class="section-heading">
                        <div>
                            <h4>Daftar Notifikasi</h4>
                            <p class="section-caption">Tabel ini memperlihatkan status pengiriman, jatuh tempo, dan aksi notifikasi per mitra.</p>
                        </div>
                    </div>

                    <div class="table-responsive table-shell">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mitra</th>
                                    <th>Kontak</th>
                                    <th>Status Notifikasi</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Jumlah Pinjaman</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPeminjaman as $item)
                                    <tr>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $item->nama_mitra }}</strong>
                                                <small>{{ $item->nomor_mitra ?: 'Nomor mitra belum tersedia' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $item->kontak }}</strong>
                                                <small>{{ $item->formatted_tgl_peminjaman ?: 'Tanggal pinjaman belum tersedia' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-pill {{ $item->notification_status_class }}">{{ $item->notification_status_label }}</span>
                                        </td>
                                        <td>{{ $item->formatted_next_due_date }}</td>
                                        <td>
                                            <span class="amount-pill">{{ $item->formatted_pokok_pinjaman_awal }}</span>
                                        </td>
                                        <td class="action-cell">
                                            @if ($item->should_show_notification_send_action)
                                                <form action="{{ route('notif.send', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="mdi mdi-send"></i> Kirim WA
                                                    </button>
                                                </form>
                                            @elseif ($item->notification_status_label === 'Pengingat Kedua Terkirim')
                                                <span class="muted-meta">Pengingat kedua sudah terkirim</span>
                                            @elseif ($item->notification_status_label === 'Terkirim')
                                                <span class="muted-meta">Notifikasi awal sudah terkirim</span>
                                            @elseif ($item->notification_status_label === 'Lunas')
                                                <span class="muted-meta">Pinjaman sudah lunas</span>
                                            @else
                                                <span class="muted-meta">Menunggu jatuh tempo</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Empty state ini menutup dua kemungkinan: data memang belum ada atau hasil filter sedang kosong. --}}
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <i class="mdi mdi-bell-off-outline"></i>
                                            <strong class="d-block mb-1">Belum ada data notifikasi</strong>
                                            <span>Coba ubah pencarian atau cek apakah data pinjaman sudah tersedia.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="footer-row">
                        {{-- Footer menjaga informasi jumlah data dan pagination tetap konsisten posisinya. --}}
                        <p class="muted-meta mb-0">Menampilkan {{ $dataPeminjaman->count() }} dari {{ $dataPeminjaman->total() }} data notifikasi.</p>
                        <div>
                            {{ $dataPeminjaman->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials._footer')
    </div>
@endsection
