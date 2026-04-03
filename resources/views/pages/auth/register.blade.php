<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
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
                                        <p class="auth-brand-label">Registrasi Pengguna</p>
                                        <h1 class="auth-brand-name">NotiLoan</h1>
                                    </div>
                                </div>

                                <div class="auth-visual-headline">
                                    <span class="auth-kicker mb-3">Tema Perkebunan Sawit</span>
                                    <h2>Buat akun admin dengan tampilan yang senada bersama sistem utama.</h2>
                                    <p>
                                        Halaman register kini memakai bahasa visual yang sama dengan login agar pengalaman
                                        masuk ke sistem terasa lebih utuh dan profesional.
                                    </p>

                                    <div class="auth-stat-grid">
                                        <div class="auth-stat">
                                            <div class="auth-stat-label">Identitas Login</div>
                                            <p class="auth-stat-value">Nomor SAP</p>
                                        </div>
                                        <div class="auth-stat">
                                            <div class="auth-stat-label">Ketentuan</div>
                                            <p class="auth-stat-value">Min. 5 Digit</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 auth-form-panel p-4 p-lg-5">
                            <div class="mb-4">
                                <span class="auth-kicker mb-3">Buat Akun</span>
                                <h4 class="auth-title mb-2">Daftarkan pengguna baru</h4>
                                <p class="auth-copy">
                                    Lengkapi nama, nomor SAP, dan password untuk membuat akun baru pada sistem NotiLoan.
                                </p>
                            </div>

                            @if ($errors->any())
                                <div class="alert auth-alert small mb-4">
                                    <ul class="mb-0 ps-3">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="auth-input-label">Nama</label>
                                    <div class="auth-input-wrap">
                                        <span class="auth-input-icon">
                                            <i class="mdi mdi-account-outline"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control auth-input"
                                            value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="auth-input-label">SAP</label>
                                    <div class="auth-input-wrap">
                                        <span class="auth-input-icon">
                                            <i class="mdi mdi-card-account-details-outline"></i>
                                        </span>
                                        <input type="text" name="sap" class="form-control auth-input"
                                            value="{{ old('sap') }}" placeholder="Masukkan minimal 5 digit SAP"
                                            inputmode="numeric" pattern="[0-9]{5,}" minlength="5" maxlength="20"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="auth-input-label">Password</label>
                                    <div class="auth-input-wrap">
                                        <span class="auth-input-icon">
                                            <i class="mdi mdi-lock-outline"></i>
                                        </span>
                                        <input type="password" name="password" class="form-control auth-input"
                                            placeholder="Masukkan password" required>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="auth-input-label">Konfirmasi Password</label>
                                    <div class="auth-input-wrap">
                                        <span class="auth-input-icon">
                                            <i class="mdi mdi-shield-check-outline"></i>
                                        </span>
                                        <input type="password" name="password_confirmation" class="form-control auth-input"
                                            placeholder="Ulangi password" required>
                                    </div>
                                </div>

                                <button class="btn auth-submit text-white w-100">
                                    Daftar
                                </button>
                            </form>

                            <div class="text-center mt-3">
                                <small class="auth-note">
                                    Sudah punya akun?
                                    <a href="{{ route('login') }}" class="fw-semibold auth-link">Login</a>
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
