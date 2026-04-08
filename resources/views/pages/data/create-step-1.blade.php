@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Tambah Data Pinjaman</p>
                <h3 class="page-title">Langkah 1 dari 3: data mitra</h3>
                <p class="page-copy">Isi identitas dasar mitra terlebih dahulu sebelum masuk ke detail pinjaman dan administrasi.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="wizard-steps">
                        <span class="wizard-step is-active">1. Data Mitra</span>
                        <span class="wizard-step">2. Data Pinjaman</span>
                        <span class="wizard-step">3. Administrasi</span>
                    </div>

                    <div class="page-card-header">
                        <div>
                            <h4>Informasi Mitra</h4>
                            <p>Bagian ini dipakai untuk menyimpan identitas yang akan dipakai di modul pinjaman, notifikasi, dan pembayaran.</p>
                        </div>
                        <a href="{{ route('data.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                    <form action="{{ route('data.store.step1') }}" method="POST">
                        @csrf

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Nomor Mitra</label>
                                <input type="text" name="nomor_mitra" class="form-control" value="{{ old('nomor_mitra') }}">
                                <small class="field-hint">Opsional, dipakai bila lembaga punya nomor mitra internal.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Virtual Account</label>
                                <input type="text" name="virtual_account" class="form-control" value="{{ old('virtual_account') }}">
                                <small class="field-hint">Opsional, isi jika mitra sudah punya nomor pembayaran khusus.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Nama Mitra <span class="text-danger">*</span></label>
                                <input type="text" name="nama_mitra" class="form-control" value="{{ old('nama_mitra') }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kontak / No. HP <span class="text-danger">*</span></label>
                                <input type="text" name="kontak" class="form-control" value="{{ old('kontak') }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kabupaten</label>
                                <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten') }}">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Sektor</label>
                                <input type="text" name="sektor" class="form-control" value="{{ old('sektor') }}">
                            </div>

                            <div class="field-card is-full">
                                <label class="field-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        <div class="form-actions is-end">
                            <button type="submit" class="btn btn-primary btn-action">Lanjut ke Data Pinjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
