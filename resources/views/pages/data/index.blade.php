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

                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <input type="text" class="form-control form-control-sm search-input"
                                        placeholder="Cari Data...">
                                    <a href="{{ route('data.create') }}">
                                        <button class="btn btn-success">
                                            Tambah Data
                                        </button>
                                    </a>

                                </div>
                            </div>
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
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
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->kontak }}</td>
                                                <td>{{ $item->tgl_peminjaman }}</td>
                                                <td>{{ $item->tgl_pengembalian }}</td>
                                                <td>{{ $item->jumlah }}</td>
                                                <td class="text-center">
                                                    <a href="{{ route('data.edit', $item->peminjaman_id) }}"
                                                        class="btn btn-sm btn-warning me-1">
                                                        <i class="mdi mdi-pencil">Edit</i>
                                                    </a>

                                                    <form action="{{ route('data.destroy', $item->peminjaman_id) }}" method="POST"
                                                        class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="mdi mdi-delete">Hapus</i>
                                                        </button>
                                                    </form>
                                                </td>

                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('partials._footer')
    </div>
@endsection
