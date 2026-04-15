@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Edit Data Pinjaman</p>
                <h3 class="page-title">Langkah 1 dari 3: perbarui data mitra</h3>
                <p class="page-copy">Ubah identitas mitra terlebih dahulu sebelum masuk ke rincian pinjaman dan administrasi.</p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="wizard-steps">
                        <span class="wizard-step is-active">1. Data Mitra</span>
                        <span class="wizard-step">2. Data Pinjaman</span>
                        <span class="wizard-step">3. Administrasi</span>
                    </div>

                    <div class="page-card-header">
                        <div>
                            <h4>Edit Identitas Mitra</h4>
                            <p>Perubahan di langkah ini hanya memengaruhi informasi mitra, tidak langsung mengubah saldo atau tenor pinjaman.</p>
                        </div>
                        <a href="{{ route('data.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                    <form action="{{ route('data.update.step1', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Nomor Mitra</label>
                                <input type="text" name="nomor_mitra" class="form-control" value="{{ old('nomor_mitra', $peminjaman->nomor_mitra) }}">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Virtual Account</label>
                                <select name="virtual_account_bank" class="form-control mb-2">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($virtualAccountBanks as $value => $label)
                                        <option value="{{ $value }}" {{ old('virtual_account_bank', $peminjaman->virtual_account_bank) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="virtual_account" class="form-control" value="{{ old('virtual_account', $peminjaman->virtual_account) }}" placeholder="Masukkan nomor virtual account">
                                <small class="field-hint">Saat ditampilkan, formatnya akan menjadi `Nama Bank - Nomor Virtual Account`.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Nama Mitra <span class="text-danger">*</span></label>
                                <input type="text" name="nama_mitra" class="form-control" value="{{ old('nama_mitra', $peminjaman->nama_mitra) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kontak <span class="text-danger">*</span></label>
                                <input type="text" name="kontak" class="form-control" value="{{ old('kontak', $peminjaman->kontak) }}" required>
                                <small class="field-hint">Nomor HP akan disimpan dalam format `(+62) 8...`.</small>
                            </div>

                            <div class="field-card is-full">
                                <label class="field-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $peminjaman->alamat) }}</textarea>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kabupaten</label>
                                <input type="text" name="kabupaten" class="form-control" value="{{ old('kabupaten', $peminjaman->kabupaten) }}">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Sektor</label>
                                <input type="text" name="sektor" class="form-control" value="{{ old('sektor', $peminjaman->sektor) }}">
                            </div>
                        </div>

                        <div class="form-actions is-end">
                            <button class="btn btn-primary btn-action" type="submit">Lanjut ke Data Pinjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
