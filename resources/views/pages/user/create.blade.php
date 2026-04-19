@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Manajemen User</p>
                <h3 class="page-title">Tambah admin baru</h3>
                <p class="page-copy">Akun yang dibuat dari halaman ini otomatis berperan sebagai admin biasa.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Form Tambah User</h4>
                            <p>Isi identitas dasar admin. Role tidak perlu dipilih karena sistem akan menetapkannya otomatis sebagai admin.</p>
                        </div>
                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <div class="context-banner">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo NotiLoan">
                        <div>
                            <strong>Role akun akan ditetapkan sebagai Admin</strong>
                            <p>Super admin hanya dibuat dari seeder agar hak akses tertinggi tetap terkendali.</p>
                        </div>
                    </div>

                    <form action="{{ route('user.store') }}" method="POST">
                        @csrf

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Nama User <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">SAP <span class="text-danger">*</span></label>
                                <input type="text" name="sap" class="form-control" value="{{ old('sap') }}"
                                    inputmode="numeric" pattern="[0-9]{5,}" minlength="5" maxlength="20"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                <small class="field-hint">SAP dipakai sebagai identitas login user ke sistem.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Konfirmasi Password <span class="text-danger">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-actions is-end">
                            <button type="submit" class="btn btn-primary btn-action">
                                <i class="mdi mdi-content-save-outline"></i>
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
