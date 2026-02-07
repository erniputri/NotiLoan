@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h4>Edit Data Peminjaman</h4>
                    <a href="{{ route('data.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('data.update', $dataPeminjaman->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Nama Mitra</label>
                            <input type="text" name="nama_mitra" class="form-control"
                                value="{{ $dataPeminjaman->nama_mitra }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Kontak</label>
                            <input type="text" name="kontak" class="form-control"
                                value="{{ $dataPeminjaman->kontak }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Tanggal Peminjaman</label>
                            <input type="date" name="tgl_peminjaman" class="form-control"
                                value="{{ $dataPeminjaman->tgl_peminjaman }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Lama Angsuran (bulan)</label>
                            <input type="number" name="lama_angsuran_bulan" class="form-control"
                                value="{{ $dataPeminjaman->lama_angsuran_bulan }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Pokok Pinjaman</label>
                            <input type="number" name="pokok_pinjaman_awal" class="form-control"
                                value="{{ $dataPeminjaman->pokok_pinjaman_awal }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Sektor</label>
                            <select name="sektor" class="form-control">
                                <option {{ $dataPeminjaman->sektor == 'Perdagangan' ? 'selected' : '' }}>Perdagangan</option>
                                <option {{ $dataPeminjaman->sektor == 'Jasa' ? 'selected' : '' }}>Jasa</option>
                                <option {{ $dataPeminjaman->sektor == 'Pertanian' ? 'selected' : '' }}>Pertanian</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Kualitas Kredit</label>
                            <select name="kualitas_kredit" class="form-control">
                                <option {{ $dataPeminjaman->kualitas_kredit == 'Lancar' ? 'selected' : '' }}>Lancar</option>
                                <option {{ $dataPeminjaman->kualitas_kredit == 'Kurang Lancar' ? 'selected' : '' }}>Kurang Lancar</option>
                                <option {{ $dataPeminjaman->kualitas_kredit == 'Macet' ? 'selected' : '' }}>Macet</option>
                            </select>
                        </div>

                    </div>

                    <div class="text-end">
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
