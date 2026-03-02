@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <h4 class="mb-4">Edit Pembayaran</h4>

                <form action="{{ route('pembayaran.update', $pembayaran->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Tanggal Pembayaran</label>
                        <input type="date"
                               name="tanggal_pembayaran"
                               class="form-control"
                               value="{{ $pembayaran->tanggal_pembayaran }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Bayar</label>
                        <input type="number"
                               name="jumlah_bayar"
                               class="form-control"
                               value="{{ $pembayaran->jumlah_bayar }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Bukti Pembayaran (Opsional)</label>
                        <input type="file"
                               name="bukti_pembayaran"
                               class="form-control">
                    </div>

                    <button class="btn btn-success">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('pembayaran.index') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
