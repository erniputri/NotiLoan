@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <h4 class="mb-4">Edit Administrasi & Jaminan</h4>

                <form action="{{ route('data.update.step3', $peminjaman->id) }}"
                      method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Administrasi --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Administrasi Awal</label>
                            <input type="number"
                                   name="administrasi_awal"
                                   class="form-control"
                                   value="{{ $peminjaman->administrasi_awal }}"
                                   required>
                        </div>

                        {{-- Kualitas Kredit --}}
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kualitas Kredit</label>
                            <select name="kualitas_kredit" class="form-control" required>
                                <option value="Lancar"
                                    {{ $peminjaman->kualitas_kredit == 'Lancar' ? 'selected' : '' }}>
                                    Lancar
                                </option>
                                <option value="Kurang Lancar"
                                    {{ $peminjaman->kualitas_kredit == 'Kurang Lancar' ? 'selected' : '' }}>
                                    Kurang Lancar
                                </option>
                                <option value="Macet"
                                    {{ $peminjaman->kualitas_kredit == 'Macet' ? 'selected' : '' }}>
                                    Macet
                                </option>
                            </select>
                        </div>

                        {{-- Keterangan Jaminan --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Keterangan Jaminan</label>
                            <textarea name="jaminan"
                                      class="form-control"
                                      rows="3"
                                      required>{{ $peminjaman->jaminan }}</textarea>
                            <small class="text-muted">
                                Contoh: SHM No. 1234 atas nama Budi
                            </small>
                        </div>

                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('data.edit.step2', $peminjaman->id) }}"
                           class="btn btn-secondary">
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
