@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <h4>Edit Administrasi & Jaminan</h4>

                <form action="{{ route('data.update.step3', $peminjaman->id) }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Administrasi Awal</label>
                            <input type="number" name="administrasi_awal" class="form-control"
                                value="{{ $peminjaman->administrasi_awal }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Kualitas Kredit</label>
                            <select name="kualitas_kredit" class="form-control">
                                <option {{ $peminjaman->kualitas_kredit == 'Lancar' ? 'selected' : '' }}>Lancar</option>
                                <option {{ $peminjaman->kualitas_kredit == 'Kurang Lancar' ? 'selected' : '' }}>Kurang Lancar</option>
                                <option {{ $peminjaman->kualitas_kredit == 'Macet' ? 'selected' : '' }}>Macet</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Upload Jaminan (Opsional)</label>
                            <input type="file" name="jaminan" class="form-control">
                            @if ($peminjaman->jaminan)
                                <small>
                                    <a href="{{ asset('storage/'.$peminjaman->jaminan) }}" target="_blank">
                                        Lihat jaminan lama
                                    </a>
                                </small>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('data.edit.step2', $peminjaman->id) }}" class="btn btn-secondary">
                            ← Kembali
                        </a>

                        <button class="btn btn-success">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
