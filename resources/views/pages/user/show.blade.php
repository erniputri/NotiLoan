@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Manajemen User</p>
                <h3 class="page-title">Detail user</h3>
                <p class="page-copy">Tinjau profil akun sebelum melakukan perubahan atau penghapusan.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Ringkasan Akun</h4>
                            <p>Informasi berikut membantu memastikan akun yang dipilih memang sesuai sebelum ditindaklanjuti.</p>
                        </div>
                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <div class="context-banner {{ $user->isSuperAdmin() ? 'is-warning' : '' }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo NotiLoan">
                        <div>
                            <strong>{{ $user->role_label }}</strong>
                            <p>
                                {{ $user->isSuperAdmin()
                                    ? 'Akun ini dikelola dari seeder dan tidak dapat diubah dari halaman user.'
                                    : 'Akun ini dapat diubah atau dihapus dari halaman manajemen user.' }}
                            </p>
                        </div>
                    </div>

                    <div class="detail-grid">
                        <div class="detail-item">
                            <span>Nama</span>
                            <strong>{{ $user->name }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>SAP</span>
                            <strong>{{ $user->sap }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Role</span>
                            <strong>{{ $user->role_label }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Status Pengelolaan</span>
                            <strong>{{ $user->isSuperAdmin() ? 'Akun Sistem' : 'Dapat Dikelola' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Dibuat Pada</span>
                            <strong>{{ optional($user->created_at)->format('Y-m-d H:i') ?: '-' }}</strong>
                        </div>
                        <div class="detail-item">
                            <span>Terakhir Diperbarui</span>
                            <strong>{{ optional($user->updated_at)->format('Y-m-d H:i') ?: '-' }}</strong>
                        </div>
                    </div>

                    <div class="detail-actions">
                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Kembali ke daftar user</a>
                        @if ($user->isAdmin())
                            <a href="{{ route('user.edit', $user) }}" class="btn btn-primary btn-action">
                                <i class="mdi mdi-pencil"></i>
                                Edit User
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
