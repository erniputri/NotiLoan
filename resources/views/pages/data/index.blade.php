@extends('partials.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card ">
                        <div class="card-body ">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                                {{-- LEFT SIDE : SEARCH --}}
                                <form method="GET" action="{{ route('data.index') }}"
                                    class="d-flex align-items-center gap-2">

                                    <div class="search-box">
                                        <i class="mdi mdi-magnify"></i>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Cari nama / kontak..." class="form-control">
                                    </div>
                                </form>

                                {{-- RIGHT SIDE : ACTION BUTTONS --}}
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <form action="{{ route('data.import.excel') }}" method="POST"
                                        enctype="multipart/form-data" class="d-flex align-items-center gap-2">

                                        @csrf

                                        <input type="file" name="file" class="form-control form-control-sm"
                                            style="width: 180px;" required>

                                        <button type="submit" class="btn btn-info btn-action">
                                            <i class="mdi mdi-upload me-1"></i>
                                            Import
                                        </button>
                                    </form>

                                    <a href="{{ route('data.create.step1') }}" class="btn btn-primary btn-action">
                                        <i class="mdi mdi-plus-circle-outline me-1"></i>
                                        Tambah Data
                                    </a>

                                    <button type="button" class="btn btn-success btn-action" data-toggle="collapse"
                                        data-target="#exportOptions" aria-expanded="false"
                                        aria-controls="exportOptions">
                                        <i class="mdi mdi-file-excel-outline me-1"></i>
                                        Pilih Kolom Export
                                    </button>
                                </div>
                            </div>

                            <div class="collapse mt-4" id="exportOptions">
                                <div class="border rounded-3 p-3 bg-white">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                        <div>
                                            <h6 class="mb-1">Opsi Export Excel</h6>
                                            <p class="text-muted mb-0">Pilih kolom yang ingin dimasukkan ke file export.
                                                Hasil export juga mengikuti data pencarian yang sedang aktif.</p>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                id="selectAllExportColumns">
                                                Pilih Semua
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                id="resetExportColumns">
                                                Reset Default
                                            </button>
                                        </div>
                                    </div>

                                    <form method="GET" action="{{ route('data.export.excel') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">

                                        <div class="row g-2">
                                            @foreach ($availableExportColumns as $key => $label)
                                                <div class="col-md-4 col-lg-3">
                                                    <label class="form-check d-flex align-items-start gap-2 border rounded-2 px-3 py-2 h-100">
                                                        <input class="form-check-input mt-1 export-column-checkbox"
                                                            type="checkbox" name="columns[]" value="{{ $key }}"
                                                            {{ in_array($key, $selectedExportColumns, true) ? 'checked' : '' }}>
                                                        <span class="small fw-medium">{{ $label }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="d-flex justify-content-end gap-2 mt-3 flex-wrap">
                                            <a href="{{ route('data.template.excel') }}" class="btn btn-outline-dark">
                                                <i class="mdi mdi-download me-1"></i>
                                                Download Template
                                            </a>
                                            <button type="submit" class="btn btn-success">
                                                <i class="mdi mdi-file-excel me-1"></i>
                                                Download Export
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Mitra</th>
                                            <th>Kontak</th>
                                            <th>Tanggal Peminjaman</th>
                                            <th>Jumlah</th>
                                            <th>Kualitas</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataPeminjaman as $item)
                                            <tr>
                                                <td>{{ $item->nama_mitra }}</td>
                                                <td>{{ $item->kontak }}</td>
                                                <td>{{ $item->tgl_peminjaman }}</td>
                                                <td>Rp {{ number_format($item->pokok_pinjaman_awal) }}</td>
                                                <td>
                                                    @if ($item->kualitas_kredit == 'Lancar')
                                                        <span class="badge bg-success">Lancar</span>
                                                    @elseif ($item->kualitas_kredit == 'Kurang Lancar')
                                                        <span class="badge bg-warning">Kurang Lancar</span>
                                                    @elseif ($item->kualitas_kredit == 'Ragu-ragu')
                                                        <span class="badge bg-info">Ragu-ragu</span>
                                                    @elseif ($item->kualitas_kredit == 'Macet')
                                                        <span class="badge bg-danger">Macet</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Diketahui</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">

                                                    <a href="{{ route('data.show', $item->id) }}"
                                                        class="btn btn-sm btn-info me-1">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </a>

                                                    <a href="{{ route('data.edit.step1', $item->id) }}"
                                                        class="btn btn-sm btn-warning me-1">
                                                        <i class="mdi mdi-pencil"></i> Edit
                                                    </a>

                                                    <form action="{{ route('data.destroy', $item->id) }}" method="POST"
                                                        class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="mdi mdi-delete"></i> Hapus
                                                        </button>
                                                    </form>

                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end align-items-center mt-4">
                                    {{ $dataPeminjaman->onEachSide(1)->links('pagination::bootstrap-5') }}
                                </div>

                            </div>
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
