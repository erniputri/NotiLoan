@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Tambah Data Pinjaman</p>
                <h3 class="page-title">Langkah 3 dari 3: administrasi dan jaminan</h3>
                <p class="page-copy">Lengkapi administrasi awal dan keterangan jaminan sebelum data pinjaman disimpan ke sistem.</p>
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
                            <h4>Administrasi dan Jaminan</h4>
                            <p>Bagian ini menutup proses input pinjaman dan melengkapi dokumen yang dibutuhkan untuk monitoring.</p>
                        </div>
                        <a href="{{ route('data.create.step2') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <form action="{{ route('data.store.final') }}" method="POST">
                        @csrf

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Administrasi Awal</label>
                                <input type="number" name="administrasi_awal" class="form-control" value="{{ old('administrasi_awal') }}" placeholder="Akan dihitung otomatis bila dikosongkan">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Nomor Surat Perjanjian <span class="text-danger">*</span></label>
                                <input type="text" name="no_surat_perjanjian" class="form-control" value="{{ old('no_surat_perjanjian') }}" required>
                            </div>

                            <div class="field-card is-full">
                                <label class="field-label">Keterangan Jaminan <span class="text-danger">*</span></label>
                                <textarea name="jaminan" class="form-control" rows="4" required>{{ old('jaminan') }}</textarea>
                                <small class="field-hint">Contoh: SHM No. 1234 atas nama Budi.</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('data.create.step2') }}" class="btn btn-outline-secondary">Kembali</a>
                            <button type="submit" class="btn btn-success btn-action">Simpan Data Pinjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
