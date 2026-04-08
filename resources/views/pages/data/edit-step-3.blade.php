@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Edit Data Pinjaman</p>
                <h3 class="page-title">Langkah 3 dari 3: perbarui administrasi</h3>
                <p class="page-copy">Langkah terakhir dipakai untuk menyesuaikan administrasi awal, kualitas kredit, dan keterangan jaminan.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="wizard-steps">
                        <span class="wizard-step">1. Data Mitra</span>
                        <span class="wizard-step">2. Data Pinjaman</span>
                        <span class="wizard-step is-active">3. Administrasi</span>
                    </div>

                    <div class="page-card-header">
                        <div>
                            <h4>Edit Administrasi dan Jaminan</h4>
                            <p>Gunakan langkah ini untuk menyempurnakan atribut pendukung setelah detail pinjaman dipastikan benar.</p>
                        </div>
                        <a href="{{ route('data.edit.step2', $peminjaman->id) }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <form action="{{ route('data.update.step3', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Administrasi Awal <span class="text-danger">*</span></label>
                                <input type="number" name="administrasi_awal" class="form-control" value="{{ old('administrasi_awal', $peminjaman->administrasi_awal) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kualitas Kredit <span class="text-danger">*</span></label>
                                <select name="kualitas_kredit" class="form-control" required>
                                    <option value="Lancar" {{ old('kualitas_kredit', $peminjaman->kualitas_kredit) == 'Lancar' ? 'selected' : '' }}>Lancar</option>
                                    <option value="Kurang Lancar" {{ old('kualitas_kredit', $peminjaman->kualitas_kredit) == 'Kurang Lancar' ? 'selected' : '' }}>Kurang Lancar</option>
                                    <option value="Ragu-ragu" {{ old('kualitas_kredit', $peminjaman->kualitas_kredit) == 'Ragu-ragu' ? 'selected' : '' }}>Ragu-ragu</option>
                                    <option value="Macet" {{ old('kualitas_kredit', $peminjaman->kualitas_kredit) == 'Macet' ? 'selected' : '' }}>Macet</option>
                                </select>
                            </div>

                            <div class="field-card is-full">
                                <label class="field-label">Keterangan Jaminan <span class="text-danger">*</span></label>
                                <textarea name="jaminan" class="form-control" rows="4" required>{{ old('jaminan', $peminjaman->jaminan) }}</textarea>
                                <small class="field-hint">Contoh: SHM No. 1234 atas nama Budi.</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('data.edit.step2', $peminjaman->id) }}" class="btn btn-outline-secondary">Kembali</a>
                            <button class="btn btn-success btn-action" type="submit">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
