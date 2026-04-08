@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Tambah Pembayaran</p>
                <h3 class="page-title">Catat transaksi pembayaran mitra</h3>
                <p class="page-copy">Isi data pembayaran untuk memperbarui sisa pokok pinjaman dan menjaga histori pembayaran tetap akurat.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Form Pembayaran</h4>
                            <p>Pilih mitra yang masih memiliki sisa pinjaman, lalu masukkan tanggal dan nominal pembayaran.</p>
                        </div>
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    @if (session('reminder'))
                        <div class="context-banner is-warning">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="NotiLoan">
                            <div>
                                <strong>Pembayaran dalam 30 hari terakhir terdeteksi</strong>
                                <p>Mitra ini sudah melakukan pembayaran dalam 30 hari terakhir. Jika ingin tetap menyimpan, klik tombol simpan lagi.</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (session('reminder'))
                            <input type="hidden" name="force" value="1">
                        @endif

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Nama Mitra <span class="text-danger">*</span></label>
                                <select name="peminjaman_id" class="form-control select-mitra" required>
                                    <option value="">-- Pilih Mitra --</option>
                                    @foreach ($peminjaman as $item)
                                        @if ($item->lama_angsuran_bulan > 0)
                                            <option value="{{ $item->id }}" {{ old('peminjaman_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama_mitra }} (Sisa Bulan: {{ $item->lama_angsuran_bulan }})
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="field-card">
                                <label class="field-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pembayaran" class="form-control" value="{{ old('tanggal_pembayaran') }}" required>
                            </div>
                            <div class="field-card">
                                <label class="field-label">Jumlah Bayar <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar') }}" placeholder="Masukkan jumlah bayar" required>
                            </div>
                            <div class="field-card">
                                <label class="field-label">Bukti Pembayaran</label>
                                <input type="file" name="bukti_pembayaran" class="form-control">
                                <small class="field-hint">Opsional, tetapi disarankan untuk dokumentasi transaksi.</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-success btn-action">Simpan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('.select-mitra').select2({
                    placeholder: "-- Pilih Mitra --",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>
    @endpush
@endsection
