@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Edit Mitra</p>
                <h3 class="page-title">{{ $mitra->nama_mitra }}</h3>
                <p class="page-copy">
                    Perubahan data mitra di halaman ini akan ikut memperbarui identitas pada semua pinjaman yang terhubung.
                </p>
            </div>

            <div class="card page-card">
                <div class="card-body">
                    <div class="page-card-header">
                        <div>
                            <h4>Perbarui Data Mitra</h4>
                            <p>Gunakan form ini untuk menjaga konsistensi profil mitra di seluruh riwayat pinjaman.</p>
                        </div>
                        <a href="{{ route('mitra.show', $mitra->id) }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>

                    <form action="{{ route('mitra.update', $mitra->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Nomor Mitra</label>
                                <input type="text" name="nomor_mitra" class="form-control"
                                    value="{{ old('nomor_mitra', $mitra->nomor_mitra) }}">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Virtual Account</label>
                                <select name="virtual_account_bank" class="form-control mb-2">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($virtualAccountBanks as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ old('virtual_account_bank', $mitra->virtual_account_bank) === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="virtual_account" class="form-control"
                                    value="{{ old('virtual_account', $mitra->virtual_account) }}"
                                    placeholder="Masukkan nomor virtual account">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Nama Mitra</label>
                                <input type="text" name="nama_mitra" class="form-control"
                                    value="{{ old('nama_mitra', $mitra->nama_mitra) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kontak</label>
                                <input type="text" name="kontak" class="form-control"
                                    value="{{ old('kontak', $mitra->kontak) }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kabupaten</label>
                                <input type="text" name="kabupaten" class="form-control"
                                    value="{{ old('kabupaten', $mitra->kabupaten) }}">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Sektor</label>
                                <input type="text" name="sektor" class="form-control"
                                    value="{{ old('sektor', $mitra->sektor) }}">
                            </div>

                            <div class="field-card is-full">
                                <label class="field-label">Alamat</label>
                                <textarea name="alamat" class="form-control" rows="4">{{ old('alamat', $mitra->alamat) }}</textarea>
                            </div>
                        </div>

                        <div class="form-actions is-end">
                            <a href="{{ route('mitra.show', $mitra->id) }}" class="btn btn-outline-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary btn-action">
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
