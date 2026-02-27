@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Tambah Data NotiLoan – Data Pinjaman</h4>
                        <a href="{{ route('data.create.step1') }}" class="btn btn-secondary">
                            ← Kembali
                        </a>
                    </div>

                    {{-- error validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('data.store.step2') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah Pinjaman (Pokok)</label>
                                <input type="number" name="pokok_pinjaman_awal" class="form-control"
                                    placeholder="Masukkan jumlah pinjaman" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Peminjaman</label>
                                <input type="date" name="tgl_peminjaman" class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Lama Angsuran (Bulan)</label>
                                <input type="number" name="lama_angsuran_bulan" class="form-control"
                                    placeholder="Contoh: 12" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">% Bunga / Tahun</label>
                                <input type="number" name="bunga_persen" class="form-control"
                                    placeholder="Masukkan bunga per tahun (%)" step="0.01" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preview Administrasi Awal</label>
                                <input type="text" id="preview_administrasi" class="form-control" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Jatuh Tempo</label>
                                <input type="text" id="tgl_jatuh_tempo" class="form-control" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Akhir Pinjaman</label>
                                <input type="text" id="tgl_akhir_pinjaman" class="form-control" readonly>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                Next
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
        @include('partials._footer')
    </div>

    {{-- SCRIPT PERHITUNGAN --}}
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
                const lama = parseInt(lamaInput.value || 0);
                const tanggal = tanggalInput.value;

                // === HITUNG ADMINISTRASI ===
                if (pokok && bunga) {
                    const hasilAdmin = pokok * (bunga / 100);
                    adminPreview.value = hasilAdmin.toLocaleString('id-ID');
                } else {
                    adminPreview.value = '';
                }

                // === HITUNG TANGGAL JATUH TEMPO ===
                if (tanggal && lama) {
                    let date = new Date(tanggal);
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

        });
    </script>
@endsection
