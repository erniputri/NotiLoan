@extends('partials.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Tambah Pembayaran</h4>

                                <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary btn-action">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                            </div>

                            <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label>Nama Mitra</label>
                                        <select name="peminjaman_id" id="peminjaman_id" class="form-control" required>
                                            <option value="">-- Pilih Mitra --</option>
                                            @foreach ($peminjaman as $item)
                                                <option value="{{ $item->id }}" data-sisa="{{ $item->pokok_sisa }}">
                                                    {{ $item->nama_mitra }}
                                                    (Sisa: Rp {{ number_format($item->pokok_sisa) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Pembayaran</label>
                                        <input type="date" name="tanggal_pembayaran" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Jumlah Bayar</label>
                                        <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control"
                                            placeholder="Masukkan jumlah bayar" required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bukti Pembayaran</label>
                                        <input type="file" name="bukti_pembayaran" class="form-control">
                                    </div>

                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-success btn-action">
                                        <i class="mdi mdi-content-save"></i> Simpan
                                    </button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('partials._footer')
    </div>

    <script>
        document.getElementById('peminjaman_id')
            .addEventListener('change', function() {

                const option = this.options[this.selectedIndex];
                const sisa = option.getAttribute('data-sisa');

                document.getElementById('jumlah_bayar').value = sisa ?? '';
            });
    </script>
@endsection
