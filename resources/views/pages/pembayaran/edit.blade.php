@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Edit Pembayaran</p>
                <h3 class="page-title">Perbarui transaksi pembayaran</h3>
                <p class="page-copy">Gunakan halaman ini untuk menyesuaikan tanggal, nominal, atau bukti pembayaran yang sudah tercatat.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Edit Pembayaran</h4>
                            <p>Perubahan pada halaman ini akan memengaruhi sisa pokok pinjaman secara otomatis melalui service pembayaran.</p>
                        </div>
                        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                    <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Tanggal Pembayaran <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_pembayaran" class="form-control" value="{{ old('tanggal_pembayaran', $pembayaran->formatted_tanggal_pembayaran) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Jumlah Bayar <span class="text-danger">*</span></label>
                                <input type="number" name="jumlah_bayar" class="form-control" value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}" required>
                            </div>

                            <div class="field-card is-full">
                                <label class="field-label">Bukti Pembayaran</label>
                                <input type="file" name="bukti_pembayaran" class="form-control">
                                <small class="field-hint">Kosongkan jika tidak ingin mengganti file bukti yang lama.</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-secondary">Batal</a>
                            <button class="btn btn-success btn-action" type="submit">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
