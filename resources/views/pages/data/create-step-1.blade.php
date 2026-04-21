@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper work-page">
            <div class="page-hero">
                <p class="page-kicker">Tambah Data Pinjaman</p>
                <h3 class="page-title">Langkah 1 dari 3: data mitra</h3>
                <p class="page-copy">Pilih mitra yang sudah ada lewat pencarian, atau kosongkan pilihan lalu isi manual untuk mitra baru.</p>
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
                            <h4>Informasi Mitra</h4>
                            <p>Gunakan pencarian untuk memilih mitra yang sudah ada. Jika mitra baru belum tersedia, isi form secara manual.</p>
                        </div>
                        <a href="{{ route('data.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>

                    <form action="{{ route('data.store.step1') }}" method="POST" id="mitraStepForm">
                        @csrf

                        <div class="context-banner">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="NotiLoan">
                            <div>
                                <strong>Pilih mitra lama atau input mitra baru</strong>
                                <p>Dropdown di bawah mendukung pencarian. Jika Anda mulai mengubah field secara manual, sistem akan menganggap data sebagai mitra baru.</p>
                            </div>
                        </div>

                        <div class="field-card is-full mb-3">
                            <label class="field-label">Pilih Mitra yang Sudah Ada</label>
                            <select name="mitra_id" id="mitraSelector" class="form-control">
                                <option value="">Input mitra baru</option>
                                @foreach ($mitraOptions as $mitra)
                                    <option value="{{ $mitra->id }}"
                                        data-nomor_mitra="{{ $mitra->nomor_mitra }}"
                                        data-virtual_account_bank="{{ $mitra->virtual_account_bank }}"
                                        data-virtual_account="{{ $mitra->virtual_account }}"
                                        data-nama_mitra="{{ $mitra->nama_mitra }}"
                                        data-kontak="{{ $mitra->kontak }}"
                                        data-alamat="{{ $mitra->alamat }}"
                                        data-kabupaten="{{ $mitra->kabupaten }}"
                                        data-sektor="{{ $mitra->sektor }}"
                                        {{ old('mitra_id') == $mitra->id ? 'selected' : '' }}>
                                        {{ $mitra->nama_mitra }}{{ $mitra->nomor_mitra ? ' - ' . $mitra->nomor_mitra : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="field-hint">Cari berdasarkan nama. Jika tidak ditemukan, biarkan kosong lalu isi data mitra baru di bawah.</small>
                        </div>

                        <div class="section-grid">
                            <div class="field-card">
                                <label class="field-label">Nomor Mitra</label>
                                <input type="text" name="nomor_mitra" id="nomor_mitra" class="form-control" value="{{ old('nomor_mitra') }}">
                                <small class="field-hint">Opsional, dipakai bila lembaga punya nomor mitra internal.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Virtual Account</label>
                                <select name="virtual_account_bank" id="virtual_account_bank" class="form-control mb-2">
                                    <option value="">Pilih Bank</option>
                                    @foreach ($virtualAccountBanks as $value => $label)
                                        <option value="{{ $value }}" {{ old('virtual_account_bank') === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="virtual_account" id="virtual_account" class="form-control" value="{{ old('virtual_account') }}" placeholder="Masukkan nomor virtual account">
                                <small class="field-hint">Opsional. Saat ditampilkan, formatnya akan menjadi `Nama Bank - Nomor Virtual Account`.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Nama Mitra <span class="text-danger">*</span></label>
                                <input type="text" name="nama_mitra" id="nama_mitra" class="form-control" value="{{ old('nama_mitra') }}" required>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kontak / No. HP <span class="text-danger">*</span></label>
                                <input type="text" name="kontak" id="kontak" class="form-control" value="{{ old('kontak') }}" required>
                                <small class="field-hint">Gunakan nomor HP mitra. Sistem akan menyimpan format menjadi `(+62) 8...`.</small>
                            </div>

                            <div class="field-card">
                                <label class="field-label">Kabupaten</label>
                                <input type="text" name="kabupaten" id="kabupaten" class="form-control" value="{{ old('kabupaten') }}">
                            </div>

                            <div class="field-card">
                                <label class="field-label">Sektor</label>
                                <input type="text" name="sektor" id="sektor" class="form-control" value="{{ old('sektor') }}">
                            </div>

                            <div class="field-card is-full">
                                <label class="field-label">Alamat</label>
                                <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        <div class="form-actions is-end">
                            <button type="submit" class="btn btn-primary btn-action">Lanjut ke Data Pinjaman</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selector = $('#mitraSelector');
            const fields = {
                nomor_mitra: document.getElementById('nomor_mitra'),
                virtual_account_bank: document.getElementById('virtual_account_bank'),
                virtual_account: document.getElementById('virtual_account'),
                nama_mitra: document.getElementById('nama_mitra'),
                kontak: document.getElementById('kontak'),
                kabupaten: document.getElementById('kabupaten'),
                sektor: document.getElementById('sektor'),
                alamat: document.getElementById('alamat'),
            };

            let isAutofilling = false;

            function fillFromSelectedOption() {
                const selectedOption = selector.find('option:selected');
                const mitraId = selectedOption.val();

                if (!mitraId) {
                    return;
                }

                isAutofilling = true;

                Object.keys(fields).forEach(function(key) {
                    const nextValue = selectedOption.data(key) ?? '';

                    if (fields[key].tagName === 'SELECT') {
                        fields[key].value = nextValue;
                    } else {
                        fields[key].value = nextValue;
                    }
                });

                isAutofilling = false;
            }

            function clearSelectedMitra() {
                if (isAutofilling || !selector.val()) {
                    return;
                }

                selector.val('').trigger('change.select2');
            }

            selector.select2({
                placeholder: 'Cari mitra yang sudah ada...',
                allowClear: true,
                width: '100%'
            });

            selector.on('change', function() {
                fillFromSelectedOption();
            });

            Object.values(fields).forEach(function(field) {
                field.addEventListener('input', clearSelectedMitra);
                field.addEventListener('change', clearSelectedMitra);
            });

            if (selector.val()) {
                fillFromSelectedOption();
            }
        });
    </script>
@endpush
