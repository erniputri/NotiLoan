@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper list-page">
            <style>
                .list-page .page-hero {
                    background: linear-gradient(135deg, #123524 0%, #1f6f50 62%, #d7efe4 100%);
                    border-radius: 24px;
                    padding: 26px 28px;
                    color: #fff;
                    box-shadow: 0 18px 36px rgba(18, 53, 36, 0.16);
                    margin-bottom: 22px;
                }

                .list-page .page-kicker {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.16em;
                    opacity: 0.8;
                    margin-bottom: 8px;
                }

                .list-page .page-title {
                    font-size: 30px;
                    font-weight: 700;
                    margin-bottom: 8px;
                }

                .list-page .page-copy {
                    margin-bottom: 0;
                    color: rgba(255, 255, 255, 0.82);
                    max-width: 680px;
                }

                .list-page .hero-stat-grid {
                    display: grid;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                    gap: 12px;
                    max-width: 420px;
                    margin-left: auto;
                }

                .list-page .hero-stat {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.12);
                    border-radius: 18px;
                    padding: 14px 16px;
                }

                .list-page .hero-stat span {
                    display: block;
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                    color: rgba(255, 255, 255, 0.72);
                    margin-bottom: 4px;
                }

                .list-page .hero-stat strong {
                    font-size: 24px;
                    font-weight: 700;
                }

                .list-page .surface-card {
                    background: #fff;
                    border: 1px solid #dcebe1;
                    border-radius: 22px;
                    box-shadow: 0 14px 30px rgba(18, 53, 36, 0.07);
                    margin-bottom: 20px;
                }

                .list-page .surface-card .card-body {
                    padding: 22px;
                }

                .list-page .section-heading {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    gap: 16px;
                    margin-bottom: 18px;
                }

                .list-page .section-heading h4,
                .list-page .section-heading h5 {
                    margin-bottom: 6px;
                    font-weight: 700;
                    color: #203126;
                }

                .list-page .section-caption {
                    margin-bottom: 0;
                    color: #6f7f74;
                    font-size: 14px;
                }

                .list-page .toolbar-grid {
                    display: grid;
                    grid-template-columns: minmax(240px, 1.2fr) minmax(280px, 1fr);
                    gap: 16px;
                    align-items: end;
                }

                .list-page .search-box {
                    position: relative;
                }

                .list-page .search-box i {
                    position: absolute;
                    left: 14px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #6f7f74;
                }

                .list-page .search-box .form-control {
                    padding-left: 42px;
                    height: 44px;
                    border-radius: 14px;
                }

                .list-page .stack-actions {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    justify-content: flex-end;
                    align-items: center;
                }

                .list-page .file-input-inline {
                    min-width: 180px;
                    max-width: 220px;
                }

                .list-page .summary-strip {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    margin-bottom: 16px;
                }

                .list-page .summary-chip {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    padding: 8px 12px;
                    border-radius: 999px;
                    background: #eff8f2;
                    color: #1f6f50;
                    font-size: 13px;
                    font-weight: 600;
                }

                .list-page .summary-chip i {
                    font-size: 16px;
                }

                .list-page .table-shell {
                    border: 1px solid #e1eee6;
                    border-radius: 18px;
                    overflow: hidden;
                }

                .list-page .table {
                    margin-bottom: 0;
                }

                .list-page .table thead th {
                    font-size: 12px;
                    text-transform: uppercase;
                    letter-spacing: 0.06em;
                    border-bottom: 1px solid #e1eee6;
                }

                .list-page .table td {
                    vertical-align: middle;
                    border-color: #edf5f0;
                    padding-top: 16px;
                    padding-bottom: 16px;
                }

                .list-page .name-cell strong {
                    display: block;
                    color: #203126;
                    font-size: 15px;
                }

                .list-page .name-cell small,
                .list-page .muted-meta {
                    color: #76877c;
                    font-size: 13px;
                }

                .list-page .amount-pill {
                    display: inline-flex;
                    align-items: center;
                    padding: 7px 12px;
                    border-radius: 999px;
                    background: #f3faf5;
                    color: #1f6f50;
                    font-weight: 700;
                }

                .list-page .quality-badge {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 110px;
                    padding: 8px 12px;
                    border-radius: 999px;
                    font-size: 12px;
                    font-weight: 700;
                }

                .list-page .quality-badge.success {
                    background: #dff3e6;
                    color: #1f6f50;
                }

                .list-page .quality-badge.warning {
                    background: #fff3d6;
                    color: #9a6a00;
                }

                .list-page .quality-badge.info {
                    background: #dff2f7;
                    color: #176f86;
                }

                .list-page .quality-badge.danger {
                    background: #fbe0e3;
                    color: #b63a4b;
                }

                .list-page .quality-badge.secondary {
                    background: #eceff1;
                    color: #66737d;
                }

                .list-page .action-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    justify-content: center;
                }

                .list-page .action-group .btn {
                    border-radius: 10px;
                }

                .list-page .empty-state {
                    padding: 40px 24px;
                    text-align: center;
                    background: #fbfefd;
                    color: #708077;
                }

                .list-page .empty-state i {
                    font-size: 36px;
                    color: #8bcfb0;
                    margin-bottom: 12px;
                    display: block;
                }

                .list-page .footer-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 12px;
                    margin-top: 18px;
                    flex-wrap: wrap;
                }

                .list-page .option-card {
                    border: 1px solid #dcebe1;
                    border-radius: 14px;
                    transition: border-color 0.2s ease, background-color 0.2s ease;
                    background: #fff;
                }

                .list-page .option-card:hover {
                    border-color: #8bcfb0;
                    background: #f8fcf9;
                }

                @media (max-width: 991.98px) {
                    .list-page .hero-stat-grid,
                    .list-page .toolbar-grid {
                        grid-template-columns: 1fr;
                    }

                    .list-page .stack-actions {
                        justify-content: flex-start;
                    }
                }
            </style>

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
                                <button type="submit" class="btn btn-primary btn-action">
                                    <i class="mdi mdi-magnify"></i>
                                    Cari
                                </button>
                                @if ($search)
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
                                    <th>Kualitas</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPeminjaman as $item)
                                    @php
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
                                    <tr>
                                        <td colspan="6" class="empty-state">
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
