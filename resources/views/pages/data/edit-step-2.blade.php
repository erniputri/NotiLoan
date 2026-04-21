@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Edit Data Pinjaman</p>
                <h3 class="page-title">Langkah 2 dari 3: perbarui detail pinjaman</h3>
                <p class="page-copy">Atur kembali pokok pinjaman, tanggal pinjaman, dan tenor. Sistem akan menjaga konsistensi sisa saldo.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="wizard-steps">
                        <span class="wizard-step">1. Data Mitra</span>
                        <span class="wizard-step is-active">2. Data Pinjaman</span>
                        <span class="wizard-step">3. Administrasi</span>
                    </div>

                    <div class="page-card-header">
                        <div>
                            <h4>Edit Detail Pinjaman</h4>
                            <p>Langkah ini menyentuh nominal dan tenor, jadi perubahan akan dihitung ulang oleh sistem sebelum disimpan.</p>
                        </div>
                        <a href="{{ route('data.edit.step1', $peminjaman->id) }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <form action="{{ route('data.update.step2', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Pokok Pinjaman Awal <span class="text-danger">*</span></label>
                                <input type="number" name="pokok_pinjaman_awal" class="form-control" value="{{ old('pokok_pinjaman_awal', $peminjaman->pokok_pinjaman_awal) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Tanggal Peminjaman <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_peminjaman" class="form-control" value="{{ old('tgl_peminjaman', $peminjaman->formatted_tgl_peminjaman) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Lama Angsuran (Bulan) <span class="text-danger">*</span></label>
                                <input type="number" name="lama_angsuran_bulan" class="form-control" value="{{ old('lama_angsuran_bulan', $peminjaman->lama_angsuran_bulan) }}" required>
                            </div>

                            <div class="field-card field-preview">
                                <label class="field-label">Bunga (%)</label>
                                <input type="number" class="form-control" value="{{ $peminjaman->bunga_persen }}" disabled>
                                <small class="field-hint">Untuk saat ini bunga hanya ditampilkan sebagai referensi.</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('data.edit.step1', $peminjaman->id) }}" class="btn btn-outline-secondary">Kembali</a>
                            <button class="btn btn-primary btn-action" type="submit">Lanjut ke Administrasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
