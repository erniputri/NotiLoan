@extends('partials.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Edit Data Peminjaman</h4>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <a href="{{ route('data.index') }}">
                                <button class="btn btn-success">
                                    Kembali
                                </button>
                            </a>

                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-info">
                            {!! session('success') !!}
                        </div>
                    @endif
                    <form action="{{ route('data.update', $dataPeminjaman->peminjaman_id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <!-- Nama -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama</label>
                                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama"
                                    value="{{ $dataPeminjaman->nama }}" required>
                            </div>

                            <!-- Kontak -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kontak</label>
                                <input type="text" name="kontak" class="form-control" placeholder="No HP / Kontak"
                                    value="{{ $dataPeminjaman->kontak }}" required>
                            </div>

                            <!-- Tanggal Peminjaman -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Peminjaman</label>
                                <input type="date" name="tgl_peminjaman" class="form-control"
                                    value="{{ $dataPeminjaman->tgl_peminjaman }}" required>
                            </div>

                            <!-- Tanggal Pengembalian -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Pengembalian</label>
                                <input type="date" name="tgl_pengembalian" class="form-control"
                                    value="{{ $dataPeminjaman->tgl_pengembalian }}" required>
                            </div>

                            <!-- Jumlah -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jumlah</label>
                                <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah"
                                    value="{{ $dataPeminjaman->jumlah }}" required>
                            </div>

                        </div>

                        <!-- Button -->
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" class="btn btn-success">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @include('partials._footer')
    </div>
@endsection
