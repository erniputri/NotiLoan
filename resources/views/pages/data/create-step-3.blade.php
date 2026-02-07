@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Tambah Data NotiLoan – Administrasi Dan Jaminan</h4>
                    <a href="{{ route('data.create.step2') }}" class="btn btn-secondary">
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

                <form action="{{ route('data.store.final') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Administrasi Awal</label>
                            <input type="number"
                                   name="administrasi_awal"
                                   class="form-control"
                                   placeholder="Masukkan biaya administrasi"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Surat Perjanjian</label>
                            <input type="text"
                                   name="no_surat_perjanjian"
                                   class="form-control"
                                   placeholder="Nomor surat perjanjian"
                                   required>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Upload Jaminan</label>
                            <input type="file"
                                   name="jaminan"
                                   class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   required>
                            <small class="text-muted">
                                Format: PDF / JPG / PNG (Max 2MB)
                            </small>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn btn-success">
                            Simpan Data
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
    @include('partials._footer')
</div>
@endsection
