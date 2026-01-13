<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    @include('partials.auth.css');
</head>
<body class="d-flex align-items-center justify-content-center vh-100">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-7">
            <div class="card auth-card shadow-lg">
                <div class="row g-0">

                    <!-- LEFT -->
                    <div class="col-md-6 auth-left d-none d-md-flex flex-column justify-content-center p-4">
                        <h3 class="fw-bold">Buat Akun 🚀</h3>
                        <p class="text-muted">
                            Daftarkan diri Anda untuk menggunakan sistem.
                        </p>
                    </div>

                    <!-- RIGHT -->
                    <div class="col-md-6 p-4">
                        <h4 class="fw-bold mb-3 text-center">Register</h4>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>

                            <button class="btn btn-success w-100">
                                Daftar
                            </button>
                        </form>

                        <div class="text-center mt-3">
                            <small>
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="fw-semibold">Login</a>
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
