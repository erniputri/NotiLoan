@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="row">

            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        {{-- HEADER --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Tambah Pembayaran</h4>

                            <a href="{{ route('pembayaran.index') }}"
                               class="btn btn-secondary btn-sm">
                                <i class="mdi mdi-arrow-left"></i> Kembali
                            </a>
                        </div>

                        {{-- FORM --}}
                        <form action="{{ route('pembayaran.store') }}"
                              method="POST"
                              enctype="multipart/form-data">

                            @csrf

                            {{-- ========================= --}}
                            {{-- REMINDER 30 HARI --}}
                            {{-- ========================= --}}
                            @if(session('reminder'))
                                <div class="alert alert-warning">
                                    ⚠ Mitra ini sudah melakukan pembayaran dalam 30 hari terakhir.
                                    Jika ingin tetap menambahkan pembayaran, klik Simpan lagi.
                                </div>

                                <input type="hidden" name="force" value="1">
                            @endif

                            {{-- ERROR VALIDATION --}}
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">

                                {{-- NAMA MITRA --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Mitra</label>
                                    <select name="peminjaman_id"
                                            class="form-control"
                                            required>
                                        <option value="">-- Pilih Mitra --</option>

                                        @foreach($peminjaman as $item)
                                            @if($item->lama_angsuran_bulan > 0)
                                                <option value="{{ $item->id }}"
                                                    {{ old('peminjaman_id') == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nama_mitra }}
                                                    (Sisa Bulan: {{ $item->lama_angsuran_bulan }})
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                {{-- TANGGAL PEMBAYARAN --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal Pembayaran</label>
                                    <input type="date"
                                           name="tanggal_pembayaran"
                                           class="form-control"
                                           value="{{ old('tanggal_pembayaran') }}"
                                           required>
                                </div>

                                {{-- JUMLAH BAYAR --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jumlah Bayar</label>
                                    <input type="number"
                                           name="jumlah_bayar"
                                           class="form-control"
                                           placeholder="Masukkan jumlah bayar"
                                           value="{{ old('jumlah_bayar') }}"
                                           required>
                                </div>

                                {{-- BUKTI PEMBAYARAN --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bukti Pembayaran</label>
                                    <input type="file"
                                           name="bukti_pembayaran"
                                           class="form-control">
                                </div>

                            </div>

                            {{-- SUBMIT --}}
                            <div class="mt-3">
                                <button type="submit"
                                        class="btn btn-success">
                                    <i class="mdi mdi-content-save"></i> Simpan
                                </button>
                            </div>

                        </form>
                        {{-- END FORM --}}

                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('partials._footer')
</div>
@endsection
