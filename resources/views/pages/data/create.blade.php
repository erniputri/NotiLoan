@extends('partials.app')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Form Penambahan Data Peminjaman</h4>
                    <a href="{{ route('data.index') }}" class="btn btn-success">Kembali</a>
                </div>

                <form action="{{ route('data.store') }}" method="POST" class="mt-4">
                    @csrf
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Mitra</label>
                            <input type="text" name="nomor_mitra" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Mitra</label>
                            <input type="text" name="nama_mitra" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kontak</label>
                            <input type="text" name="kontak" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Peminjaman</label>
                            <input type="date" name="tgl_peminjaman" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Jatuh Tempo</label>
                            <input type="date" name="tgl_jatuh_tempo" class="form-control" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Lama Angsuran (Bulan)</label>
                            <input type="number" name="lama_angsuran_bulan" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Bunga (%)</label>
                            <input type="number" step="0.01" name="bunga_persen" class="form-control">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sektor</label>
                            <input type="text" name="sektor" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pokok Pinjaman</label>
                            <input type="number" name="pokok_pinjaman_awal" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Administrasi Awal</label>
                            <input type="number" name="administrasi_awal" class="form-control">
                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-success">Simpan Data</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    @include('partials._footer')
</div>
@endsection
