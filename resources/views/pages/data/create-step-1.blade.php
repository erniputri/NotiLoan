@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Tambah Data NotiLoan – Step 1</h4>
                    <a href="{{ route('data.index') }}" class="btn btn-secondary">Batal</a>
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

                <form action="{{ route('data.store.step1') }}" method="POST">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Mitra</label>
                            <input type="text" name="nomor_mitra" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Virtual Account</label>
                            <input type="text" name="virtual_account" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Mitra <span class="text-danger">*</span></label>
                            <input type="text" name="nama_mitra" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kontak (No HP) <span class="text-danger">*</span></label>
                            <input type="text" name="kontak" class="form-control" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kabupaten</label>
                            <input type="text" name="kabupaten" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Sektor</label>
                            <input type="text" name="sektor" class="form-control">
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            Lanjut ke Step 2 →
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
    @include('partials._footer')
</div>
@endsection
