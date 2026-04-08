@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper list-page">
            

            {{-- Hero ini memberi ringkasan paling cepat tentang fungsi halaman data pinjaman. --}}
            <div class="page-hero">
                <div class="row align-items-center">
                    <div class="col-xl-7 mb-4 mb-xl-0">
                        <p class="page-kicker">Data Pinjaman</p>
                        <h3 class="page-title">Kelola data nasabah dan pinjaman</h3>
                        <p class="page-copy">
                            Halaman ini dipakai untuk mencari, meninjau, menambah, mengubah, menghapus, dan menyiapkan
                            export data pinjaman. Susunannya dibuat ringkas supaya admin lebih cepat membaca isi tabel.
                        </p>
                    </div>
                    <div class="col-xl-5">
                        <div class="hero-stat-grid">
                            <div class="hero-stat">
                                <span>Total Data</span>
                                <strong>{{ $dataPeminjaman->total() }}</strong>
                            </div>
                            <div class="hero-stat">
                                <span>Total Nominal</span>
                                <strong>Rp {{ number_format($totalLoanAmount, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    {{-- Area filter dan aksi dipisah dari tabel agar tombol utama lebih mudah ditemukan admin. --}}
                    <div class="section-heading">
                        <div>
                            <h4>Filter dan Aksi</h4>
                            <p class="section-caption">Cari data lebih cepat dan gunakan aksi utama tanpa harus berpindah-pindah area.</p>
                        </div>
                    </div>

                    <div class="toolbar-grid">
                        <form method="GET" action="{{ route('data.index') }}">
                            <label class="muted-meta mb-2 d-block">Cari data pinjaman</label>
                            <div class="d-flex flex-wrap gap-2">
                                <div class="search-box flex-grow-1">
                                    <i class="mdi mdi-magnify"></i>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari nama, kontak, kabupaten, atau sektor..." class="form-control">
                                </div>
                                <select name="status" class="form-control" style="max-width: 180px;">
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ $status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="lunas" {{ $status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-magnify"></i>
                                    Cari
                                </button>
                                @if ($search || $status)
                                    <a href="{{ route('data.index') }}" class="btn btn-outline-secondary btn-action">
                                        Reset
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div>
                            <label class="muted-meta mb-2 d-block">Aksi cepat</label>
                            <div class="stack-actions">
                                <form action="{{ route('data.import.excel') }}" method="POST" enctype="multipart/form-data"
                                    class="d-flex align-items-center flex-wrap gap-2">
                                    @csrf
                                    <input type="file" name="file" class="form-control form-control-sm file-input-inline" required>
                                    <button type="submit" class="btn btn-info btn-action">
                                        <i class="mdi mdi-upload"></i>
                                        Import
                                    </button>
                                </form>

                                <a href="{{ route('data.create.step1') }}" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-plus-circle-outline"></i>
                                    Tambah Data
                                </a>

                                <button type="button" class="btn btn-success btn-action" data-toggle="collapse"
                                    data-target="#exportOptions" aria-expanded="false" aria-controls="exportOptions">
                                    <i class="mdi mdi-file-excel-outline"></i>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Panel export dibuat collapse agar fleksibel, tetapi tidak memakan ruang permanen di atas tabel. --}}
                    <div class="collapse mt-4" id="exportOptions">
                        <div class="border rounded-3 p-3 bg-white">
                            <div class="section-heading mb-3">
                                <div>
                                    <h5>Opsi Export Excel</h5>
                                    <p class="section-caption">Pilih kolom sesuai kebutuhan laporan. Hasil export akan mengikuti filter pencarian yang aktif.</p>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="button" class="btn btn-sm btn-outline-success" id="selectAllExportColumns">
                                        Pilih Semua
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="resetExportColumns">
                                        Reset Default
                                    </button>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('data.export.excel') }}">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="status" value="{{ $status }}">

                                <div class="row g-2">
                                    @foreach ($availableExportColumns as $key => $label)
                                        <div class="col-md-4 col-lg-3">
                                            <label class="form-check d-flex align-items-start gap-2 px-3 py-2 h-100 option-card">
                                                <input class="form-check-input mt-1 export-column-checkbox" type="checkbox"
                                                    name="columns[]" value="{{ $key }}"
                                                    {{ in_array($key, $selectedExportColumns, true) ? 'checked' : '' }}>
                                                <span class="small fw-medium">{{ $label }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-3 flex-wrap">
                                    <a href="{{ route('data.template.excel') }}" class="btn btn-outline-dark">
                                        <i class="mdi mdi-download"></i>
                                        Download Template
                                    </a>
                                    <button type="submit" class="btn btn-success">
                                        <i class="mdi mdi-file-excel"></i>
                                        Download Export
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card surface-card">
                <div class="card-body">
                    {{-- Bagian ini adalah area kerja utama admin untuk membaca status pinjaman dan mengambil aksi. --}}
                    <div class="section-heading">
                        <div>
                            <h4>Daftar Pinjaman</h4>
                            <p class="section-caption">Tabel utama untuk meninjau data nasabah peminjam dan status kualitas kreditnya.</p>
                        </div>
                    </div>

                    <div class="summary-strip">
                        <span class="summary-chip">
                            <i class="mdi mdi-database"></i>
                            Menampilkan {{ $dataPeminjaman->count() }} dari {{ $dataPeminjaman->total() }} data
                        </span>
                        <a href="{{ route('data.index') }}" class="summary-chip {{ ! $status ? 'is-active' : '' }}">
                            <i class="mdi mdi-view-grid-outline"></i>
                            Semua
                        </a>
                        <a href="{{ route('data.index', ['status' => 'aktif', 'search' => $search]) }}"
                            class="summary-chip {{ $status === 'aktif' ? 'is-active' : '' }}">
                            <i class="mdi mdi-progress-clock"></i>
                            Aktif: {{ $activeLoanCount }}
                        </a>
                        <a href="{{ route('data.index', ['status' => 'lunas', 'search' => $search]) }}"
                            class="summary-chip {{ $status === 'lunas' ? 'is-active' : '' }}">
                            <i class="mdi mdi-check-decagram"></i>
                            Lunas: {{ $settledLoanCount }}
                        </a>
                        @if ($search)
                            <span class="summary-chip">
                                <i class="mdi mdi-filter-outline"></i>
                                Filter aktif: "{{ $search }}"
                            </span>
                        @endif
                    </div>

                    <div class="table-responsive table-shell">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mitra</th>
                                    <th>Kontak</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Kualitas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPeminjaman as $item)
                                    @php
                                        $loanStatusClass = $item->pokok_sisa == 0 ? 'success' : 'warning';
                                        $loanStatusLabel = $item->pokok_sisa == 0 ? 'Lunas' : 'Aktif';

                                        // Class badge dipisah dari teks kualitas agar warna status tetap konsisten di tabel.
                                        $qualityClass = match ($item->kualitas_kredit) {
                                            'Lancar' => 'success',
                                            'Kurang Lancar' => 'warning',
                                            'Ragu-ragu' => 'info',
                                            'Macet' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $item->nama_mitra }}</strong>
                                                <small>{{ $item->nomor_mitra ?: 'Nomor mitra belum tersedia' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="name-cell">
                                                <strong>{{ $item->kontak }}</strong>
                                                <small>{{ $item->kabupaten ?: 'Kabupaten belum diisi' }}</small>
                                            </div>
                                        </td>
                                        <td>{{ optional($item->tgl_peminjaman)->format('Y-m-d') }}</td>
                                        <td>
                                            <span class="amount-pill">Rp {{ number_format($item->pokok_pinjaman_awal, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="loan-status-badge {{ $loanStatusClass }}">
                                                {{ $loanStatusLabel }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="quality-badge {{ $qualityClass }}">
                                                {{ $item->kualitas_kredit ?: 'Tidak Diketahui' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="{{ route('data.show', $item->id) }}" class="btn btn-sm btn-info">
                                                    <i class="mdi mdi-eye"></i> Detail
                                                </a>
                                                <a href="{{ route('data.edit.step1', $item->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('data.destroy', $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="mdi mdi-delete"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    {{-- Empty state ini membantu saat data memang belum ada atau hasil pencarian kosong. --}}
                                    <tr>
                                        <td colspan="7" class="empty-state">
                                            <i class="mdi mdi-database-search"></i>
                                            <strong class="d-block mb-1">Data pinjaman belum ditemukan</strong>
                                            <span>Ubah kata kunci pencarian atau tambahkan data pinjaman baru.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="footer-row">
                        {{-- Footer tabel dipakai untuk petunjuk singkat dan navigasi antar halaman data. --}}
                        <p class="muted-meta mb-0">Gunakan tombol detail untuk melihat data lengkap per mitra sebelum melakukan perubahan.</p>
                        <div>
                            {{ $dataPeminjaman->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script kecil ini hanya mengatur interaksi checklist export agar user tidak perlu centang satu per satu.
            const checkboxes = Array.from(document.querySelectorAll('.export-column-checkbox'));
            const selectAllButton = document.getElementById('selectAllExportColumns');
            const resetButton = document.getElementById('resetExportColumns');
            const defaultColumns = @json($selectedExportColumns);

            if (selectAllButton) {
                selectAllButton.addEventListener('click', function() {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = true;
                    });
                });
            }

            if (resetButton) {
                resetButton.addEventListener('click', function() {
                    checkboxes.forEach(function(checkbox) {
                        checkbox.checked = defaultColumns.includes(checkbox.value);
                    });
                });
            }
        });
    </script>
@endpush
