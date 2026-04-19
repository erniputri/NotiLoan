@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper list-page list-page--user">
            <div class="page-hero">
                <div class="row align-items-center">
                    <div class="col-xl-7 mb-4 mb-xl-0">
                        <p class="page-kicker">Manajemen User</p>
                        <h3 class="page-title">Admin Sistem</h3>
                        <p class="page-copy">
                            Pantau, tambah, dan kelola akses admin dengan mudah dan aman.
                        </p>
                    </div>
                    <div class="col-xl-5">
                        <div class="hero-stat-grid">
                            <div class="hero-stat">
                                <span>Total User</span>
                                <strong>{{ $totalUsers }}</strong>
                            </div>
                            <div class="hero-stat">
                                <span>Admin</span>
                                <strong>{{ $totalAdmins }}</strong>
                            </div>
                            <div class="hero-stat">
                                <span>Super Admin</span>
                                <strong>{{ $totalSuperAdmins }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    <div class="section-heading">
                        <div>
                            <h4>Filter dan Aksi</h4>
                            <p class="section-caption">Cari user berdasarkan nama atau SAP, lalu tambahkan admin baru bila diperlukan.</p>
                        </div>
                    </div>

                    <div class="toolbar-grid">
                        <form method="GET" action="{{ route('user.index') }}">
                            <label class="muted-meta mb-2 d-block">Cari user</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="search-box flex-grow-1">
                                    <i class="mdi mdi-magnify"></i>
                                    <input type="text" name="search" value="{{ $search }}" class="form-control"
                                        placeholder="Cari nama atau SAP...">
                                </div>
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-magnify"></i>
                                    Cari
                                </button>
                                @if ($search)
                                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary btn-action">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div>
                            <label class="muted-meta mb-2 d-block">Aksi cepat</label>
                            <div class="stack-actions">
                                <span class="surface-note">
                                    <i class="mdi mdi-shield-account-outline"></i>
                                    manage admin
                                </span>
                                <a href="{{ route('user.create') }}" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-account-plus-outline"></i>
                                    Tambah Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    <div class="section-heading">
                        <div>
                            <h4>Daftar User</h4>
                            <p class="section-caption">User dengan role super admin bersifat akun sistem dan hanya dapat dibaca dari halaman ini.</p>
                        </div>
                    </div>

                    <div class="summary-strip">
                        <span class="summary-chip">
                            <i class="mdi mdi-account-multiple-outline"></i>
                            Menampilkan {{ $users->count() }} dari {{ $users->total() }} user
                        </span>
                        @if ($search)
                            <span class="summary-chip">
                                <i class="mdi mdi-filter-outline"></i>
                                Filter aktif: "{{ $search }}"
                            </span>
                        @endif
                    </div>

                    <div class="table-responsive table-shell">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>SAP</th>
                                    <th>Role</th>
                                    <th>Dibuat</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $user->name }}</strong>
                                                <small>{{ $user->is(auth()->user()) ? 'Akun yang sedang login' : 'Akun sistem' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ $user->sap }}</td>
                                        <td>
                                            <span class="status-pill {{ $user->role_badge_class }}">{{ $user->role_label }}</span>
                                        </td>
                                        <td>{{ optional($user->created_at)->format('Y-m-d H:i') ?: '-' }}</td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('user.show', $user) }}" class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i> Detail
                                                </a>
                                                @if ($user->isAdmin())
                                                    <a href="{{ route('user.edit', $user) }}" class="btn btn-sm btn-warning">
                                                        <i class="mdi mdi-pencil"></i> Edit
                                                    </a>
                                                    <form action="{{ route('user.destroy', $user) }}" method="POST"
                                                        data-confirm-message="Hapus user ini?">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="mdi mdi-delete"></i> Hapus
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="muted-meta">Akun sistem</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="empty-state">
                                            <i class="mdi mdi-account-search-outline"></i>
                                            <strong class="d-block mb-1">User belum ditemukan</strong>
                                            <span>Coba ubah kata kunci pencarian atau tambahkan admin baru.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="footer-row">
                        <p class="muted-meta mb-0">Gunakan halaman ini untuk menjaga siapa saja yang dapat masuk ke sistem.</p>
                        <div>
                            {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
