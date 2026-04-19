@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Manajemen User</p>
                <h3 class="page-title">Edit user admin</h3>
                <p class="page-copy">Perubahan pada halaman ini hanya berlaku untuk akun admin biasa.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Form Edit User</h4>
                            <p>Ubah nama atau SAP jika dibutuhkan. Password boleh dikosongkan jika tidak ingin diubah.</p>
                        </div>
                        <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <div class="context-banner">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo NotiLoan">
                        <div>
                            <strong>Anda sedang mengubah akun admin</strong>
                            <p>Password hanya akan diperbarui jika Anda mengisi field password baru.</p>
                        </div>
                    </div>

                    <form action="{{ route('user.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Nama User <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">SAP <span class="text-danger">*</span></label>
                                <input type="text" name="sap" class="form-control"
                                    value="{{ old('sap', $user->sap) }}" inputmode="numeric" pattern="[0-9]{5,}"
                                    minlength="5" maxlength="20"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Password Baru</label>
                                <input type="password" name="password" class="form-control">
                                <small class="field-hint">Kosongkan jika password tidak ingin diubah.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="form-actions is-end">
                            <button type="submit" class="btn btn-primary btn-action">
                                <i class="mdi mdi-content-save-outline"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
