@extends('partials.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <div class="card">
            <div class="card-body">

                <h4 class="mb-4">Detail Pembayaran</h4>

                <table class="table table-bordered">
                    <tr>
                        <th>Nama Mitra</th>
                        <td>{{ $pembayaran->peminjaman->nama_mitra }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Pembayaran</th>
                        <td>{{ $pembayaran->tanggal_pembayaran }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Bayar</th>
                        <td>Rp {{ number_format($pembayaran->jumlah_bayar) }}</td>
                    </tr>
                    <tr>
                        <th>Sisa Pinjaman</th>
                        <td>Rp {{ number_format($pembayaran->peminjaman->pokok_sisa) }}</td>
                    </tr>
                    <tr>
                        <th>Bukti</th>
                        <td>
                            @if ($pembayaran->bukti_pembayaran)
                                <a href="{{ asset('storage/'.$pembayaran->bukti_pembayaran) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-info">
                                    Lihat Bukti
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>

                <a href="{{ route('pembayaran.index') }}"
                   class="btn btn-secondary mt-3">
                    Kembali
                </a>

            </div>
        </div>

    </div>
</div>
@endsection
