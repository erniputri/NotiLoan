@extends('partials.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">

                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">

                            {{-- HEADER --}}
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Data Pembayaran</h4>

                                <form method="GET" action="{{ route('pembayaran.index') }}"
                                    class="d-flex align-items-center gap-2">

                                    <div class="search-box">
                                        <i class="mdi mdi-magnify"></i>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Cari nama mitra..." class="form-control">
                                    </div>

                                    <a href="{{ route('pembayaran.create') }}" class="btn btn-primary btn-action">
                                        <i class="mdi mdi-plus-circle-outline me-1"></i>
                                        Tambah Pembayaran
                                    </a>
                                </form>
                            </div>

                            {{-- TABLE --}}
                            <div class="table-responsive pt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Nama Mitra</th>
                                            <th>Jumlah Pinjaman</th>
                                            <th>Tanggal Bayar</th>
                                            <th>Jumlah Bayar</th>
                                            <th>Sisa</th>
                                            <th>Status</th>
                                            <th>Bukti</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($pembayaran as $item)
                                            <tr>
                                                <td>{{ $item->peminjaman->nama_mitra }}</td>
                                                <td>
                                                    Rp {{ number_format($item->peminjaman->pokok_pinjaman_awal) }}
                                                </td>
                                                <td>{{ $item->tanggal_pembayaran }}</td>
                                                <td>
                                                    Rp {{ number_format($item->jumlah_bayar) }}
                                                </td>
                                                <td>
                                                    Rp {{ number_format($item->peminjaman->pokok_sisa) }}
                                                </td>

                                                <td>
                                                    @if ($item->peminjaman->pokok_sisa == 0)
                                                        <span class="badge bg-success">Lunas</span>
                                                    @elseif ($item->peminjaman->pokok_sisa < $item->peminjaman->pokok_pinjaman_awal)
                                                        <span class="badge bg-warning">Cicil</span>
                                                    @else
                                                        <span class="badge bg-danger">Belum Bayar</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($item->bukti_pembayaran)
                                                        <a href="{{ asset('storage/' . $item->bukti_pembayaran) }}"
                                                            target="_blank" class="btn btn-sm btn-info">
                                                            <i class="mdi mdi-eye"></i> Lihat
                                                        </a>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak ada</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted">
                                                    Data pembayaran belum tersedia
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{-- INFO + PAGINATION --}}
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div class="text-muted">
                                        Menampilkan {{ $pembayaran->count() }}
                                        dari {{ $pembayaran->total() }} data
                                    </div>

                                    {{ $pembayaran->links() }}
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
