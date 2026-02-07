@extends('partials.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card ">
                        <div class="card-body ">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Data NotiLoan</h4>

                                <form method="GET" action="{{ route('data.index') }}"
                                    class="d-flex align-items-center gap-2">

                                    <div class="search-box">
                                        <i class="mdi mdi-magnify"></i>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Cari nama / kontak..." class="form-control">
                                    </div>

                                    <a href="{{ route('data.create.step1') }}" class="btn btn-primary btn-action">
                                        <i class="mdi mdi-plus-circle-outline me-1"></i>
                                        Tambah Data
                                    </a>

                                    <a href="{{ route('data.export.excel') }}" class="btn btn-success btn-action">
                                        <i class="mdi mdi-file-excel-outline me-1"></i>
                                        Export Excel
                                    </a>
                                </form>

                            </div>
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Mitra</th>
                                            <th>Kontak</th>
                                            <th>Tanggal Peminjaman</th>
                                            <th>Tanggal Pengembalian</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dataPeminjaman as $item)
                                            <tr>
                                                <td>{{ $item->nama_mitra }}</td>
                                                <td>{{ $item->kontak }}</td>
                                                <td>{{ $item->tgl_peminjaman }}</td>
                                                <td>{{ $item->tgl_jatuh_tempo }}</td>
                                                <td>Rp {{ number_format($item->pokok_pinjaman_awal) }}</td>
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
                                <div class="text-muted mb-2">
                                    Menampilkan {{ $dataPeminjaman->count() }}
                                    dari {{ $dataPeminjaman->total() }} data
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
