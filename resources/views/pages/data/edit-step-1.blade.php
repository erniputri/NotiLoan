@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <h4>Edit Data Mitra</h4>

                <form action="{{ route('data.update.step1', $peminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nomor Mitra</label>
                            <input type="text" name="nomor_mitra" class="form-control"
                                value="{{ $peminjaman->nomor_mitra }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Virtual Account</label>
                            <input type="text" name="virtual_account" class="form-control"
                                value="{{ $peminjaman->virtual_account }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Nama Mitra</label>
                            <input type="text" name="nama_mitra" class="form-control"
                                value="{{ $peminjaman->nama_mitra }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Kontak</label>
                            <input type="text" name="kontak" class="form-control"
                                value="{{ $peminjaman->kontak }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control">{{ $peminjaman->alamat }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control"
                                value="{{ $peminjaman->kabupaten }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Sektor</label>
                            <input type="text" name="sektor" class="form-control"
                                value="{{ $peminjaman->sektor }}">
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary">
                            Lanjut ke Step 2 →
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
