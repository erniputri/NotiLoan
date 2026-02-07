@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title mb-0">Data NotiLoan</h4>

                                <input type="text" class="form-control form-control-sm w-25" placeholder="Cari Data...">
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="text-center">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Kontak</th>
                                            <th>Status</th>
                                            <th>Tanggal Jatuh Tempo</th>
                                            <th>Jumlah</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($dataPeminjaman as $item)
                                            <tr>
                                                <td>{{ $item->nama_mitra }}</td>
                                                <td>{{ $item->kontak }}</td>

                                                <td class="text-center">
                                                    @if ($item->notifikasi)
                                                        @if ($item->notifikasi->status)
                                                            <span class="badge bg-success">Terkirim</span>
                                                        @else
                                                            <span class="badge bg-warning">Pending</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Belum Dikirim</span>
                                                    @endif
                                                </td>

                                                <td>{{ $item->tgl_jatuh_tempo }}</td>
                                                <td>Rp {{ number_format($item->pokok_pinjaman_awal, 0, ',', '.') }}</td>

                                                <td class="text-center">
                                                    @if (!$item->notifikasi || !$item->notifikasi->status)
                                                        <a href=""
                                                            class="btn btn-sm btn-primary">
                                                            Kirim WA
                                                        </a>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">
                                                    Data belum tersedia
                                                </td>
                                            </tr>
                                        @endforelse
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
