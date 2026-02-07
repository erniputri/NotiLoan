@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <h4>Edit Data Pinjaman</h4>

                <form action="{{ route('data.update.step2', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Pokok Pinjaman Awal</label>
                            <input type="number" name="pokok_pinjaman_awal" class="form-control"
                                value="{{ $peminjaman->pokok_pinjaman_awal }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Tanggal Peminjaman</label>
                            <input type="date" name="tgl_peminjaman" class="form-control"
                                value="{{ $peminjaman->tgl_peminjaman }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Lama Angsuran (bulan)</label>
                            <input type="number" name="lama_angsuran_bulan" class="form-control"
                                value="{{ $peminjaman->lama_angsuran_bulan }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Bunga (%)</label>
                            <input type="number" class="form-control"
                                value="{{ $peminjaman->bunga_persen }}" disabled>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('data.edit.step1', $peminjaman->id) }}" class="btn btn-secondary">
                            ← Kembali
                        </a>

                        <button class="btn btn-primary">
                            Lanjut ke Step 3 →
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
