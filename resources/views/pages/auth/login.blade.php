<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    @include('partials.auth.css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
</head>
<body>

<div class="auth-shell d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11 col-xxl-10">
                <div class="card auth-card shadow-lg">
                    <div class="row g-0">

                        <div class="col-lg-6 d-none d-lg-flex">
                            <div class="auth-visual w-100">
                                <div class="auth-brand">
                                    <div class="auth-brand-mark">
                                        <img src="{{ asset('assets/images/logo.png') }}" alt="Logo NotiLoan">
                                    </div>
                                    <div>
                                        <p class="auth-brand-label">Sistem Monitoring</p>
                                        <h1 class="auth-brand-name">NotiLoan</h1>
                                    </div>
                                </div>

                                <div class="auth-visual-headline">
                                    <span class="auth-kicker mb-3">PT. Perkebunan Nusantara IV Regional III</span>
                                    <h2>Akses mudah untuk pengelolaan pinjaman mitra Anda</h2>
                                    <p>
                                        Pantau, atur, dan dapatkan notifikasi penting dalam satu dashboard NotiLoan.
                                    </p>

                                    <div class="auth-stat-grid">
                                        <div class="auth-stat">
                                            <div class="auth-stat-label">Unit Kerja</div>
                                            <p class="auth-stat-value auth-stat-value--compact">Divisi Tanggung Jawab Sosial Lingkungan</p>
                                        </div>
                                        <div class="auth-stat">
                                            <div class="auth-stat-label">Layanan Utama</div>
                                            <p class="auth-stat-value auth-stat-value--compact">Pengelolaan Pinjaman Mitra</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 auth-form-panel p-4 p-lg-5">
                            <div class="mb-4">
                                <span class="auth-kicker mb-3">Akses Dashboard</span>
                                <h4 class="auth-title mb-2">Masuk ke akun Anda</h4>
                                <p class="auth-copy">
                                    Gunakan nomor SAP dan password terdaftar untuk melanjutkan ke dashboard NotiLoan.
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert auth-alert small mb-4">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="auth-input-label">SAP</label>
                                    <div class="auth-input-wrap">
                                        <span class="auth-input-icon">
                                            <i class="mdi mdi-card-account-details-outline"></i>
                                        </span>
                                        <input type="text" name="sap" class="form-control auth-input"
                                            value="{{ old('sap') }}" placeholder="Masukkan minimal 5 digit SAP"
                                            inputmode="numeric" pattern="[0-9]{5,}" minlength="5" maxlength="20"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" required autofocus>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="auth-input-label">Password</label>
                                    <div class="auth-input-wrap">
                                        <span class="auth-input-icon">
                                            <i class="mdi mdi-lock-outline"></i>
                                        </span>
                                        <input type="password" name="password" class="form-control auth-input"
                                            placeholder="Masukkan password" required>
                                    </div>
                                </div>

                                <button class="btn auth-submit text-white w-100">
                                    Login
                                </button>
                            </form>

                            <div class="text-center mt-3">
                                <small class="auth-note">
                                    Belum punya akun?
                                    <a href="{{ route('register') }}" class="fw-semibold auth-link">Daftar</a>
                                </small>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
