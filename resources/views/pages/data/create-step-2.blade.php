@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Tambah Data Pinjaman</p>
                <h3 class="page-title">Langkah 2 dari 3: detail pinjaman</h3>
                <p class="page-copy">Tentukan nilai pinjaman, tenor, dan tanggal pinjaman. Sistem akan membantu menyiapkan preview jatuh tempo.</p>
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
                            <h4>Informasi Pinjaman</h4>
                            <p>Nilai pokok, bunga, dan tenor di bawah ini akan dipakai untuk membentuk sisa pinjaman dan jadwal notifikasi.</p>
                        </div>
                        <a href="{{ route('data.create.step1') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <form action="{{ route('data.store.step2') }}" method="POST">
                        @csrf

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Jumlah Pinjaman (Pokok) <span class="text-danger">*</span></label>
                                <input type="number" name="pokok_pinjaman_awal" class="form-control" value="{{ old('pokok_pinjaman_awal') }}" placeholder="Masukkan jumlah pinjaman" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Tanggal Peminjaman <span class="text-danger">*</span></label>
                                <input type="date" name="tgl_peminjaman" class="form-control" value="{{ old('tgl_peminjaman') }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Lama Angsuran (Bulan) <span class="text-danger">*</span></label>
                                <input type="number" name="lama_angsuran_bulan" class="form-control" value="{{ old('lama_angsuran_bulan') }}" placeholder="Contoh: 12" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Bunga (%) <span class="text-danger">*</span></label>
                                <input type="number" name="bunga_persen" class="form-control" value="{{ old('bunga_persen') }}" step="0.01" placeholder="Masukkan bunga per tahun" required>
                            </div>

                            <div class="field-card field-preview">
                                <label class="field-label">Preview Administrasi Awal</label>
                                <input type="text" id="preview_administrasi" class="form-control" readonly>
                                <small class="field-hint">Preview ini membantu admin sebelum masuk ke langkah administrasi.</small>
                            </div>

                            <div class="field-card field-preview">
                                <label class="field-label">Tanggal Jatuh Tempo</label>
                                <input type="text" id="tgl_jatuh_tempo" class="form-control" readonly>
                            </div>

                            <div class="field-card field-preview">
                                <label class="field-label">Tanggal Akhir Pinjaman</label>
                                <input type="text" id="tgl_akhir_pinjaman" class="form-control" readonly>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('data.create.step1') }}" class="btn btn-outline-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary btn-action">Lanjut ke Administrasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const pokokInput = document.querySelector('[name="pokok_pinjaman_awal"]');
                const bungaInput = document.querySelector('[name="bunga_persen"]');
                const lamaInput = document.querySelector('[name="lama_angsuran_bulan"]');
                const tanggalInput = document.querySelector('[name="tgl_peminjaman"]');
                const adminPreview = document.getElementById('preview_administrasi');
                const tempoField = document.getElementById('tgl_jatuh_tempo');
                const akhirField = document.getElementById('tgl_akhir_pinjaman');

                function hitungSemua() {
                    const pokok = parseFloat(pokokInput.value || 0);
                    const bunga = parseFloat(bungaInput.value || 0);
                    const lama = parseInt(lamaInput.value || 0, 10);
                    const tanggal = tanggalInput.value;

                    adminPreview.value = pokok && bunga ? (pokok * (bunga / 100)).toLocaleString('id-ID') : '';

                    if (tanggal && lama) {
                        const date = new Date(tanggal);
                        date.setMonth(date.getMonth() + lama);
                        const formatted = date.toISOString().split('T')[0];
                        tempoField.value = formatted;
                        akhirField.value = formatted;
                    } else {
                        tempoField.value = '';
                        akhirField.value = '';
                    }
                }

                pokokInput.addEventListener('input', hitungSemua);
                bungaInput.addEventListener('input', hitungSemua);
                lamaInput.addEventListener('input', hitungSemua);
                tanggalInput.addEventListener('change', hitungSemua);
                hitungSemua();
            });
        </script>
    @endpush
@endsection
