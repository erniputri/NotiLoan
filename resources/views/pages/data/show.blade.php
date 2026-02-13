@extends('partials.app')

@section('content')
    <div class="main-panel">
        <div class="content-wrapper">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4>Detail Data Peminjaman</h4>
                        <a href="{{ route('data.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>
                    </div>

                    <table class="table table-bordered">
                        <tr>
                            <th>Nama Mitra</th>
                            <td>{{ $peminjaman->nama_mitra }}</td>
                        </tr>
                        <tr>
                            <th>Kontak</th>
                            <td>{{ $peminjaman->kontak }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>{{ $peminjaman->alamat }}</td>
                        </tr>
                        <tr>
                            <th>Kabupaten</th>
                            <td>{{ $peminjaman->kabupaten }}</td>
                        </tr>
                        <tr>
                            <th>Sektor</th>
                            <td>{{ $peminjaman->sektor }}</td>
                        </tr>
                        <tr>
                            <th>Pokok Pinjaman</th>
                            <td>Rp {{ number_format($peminjaman->pokok_pinjaman_awal) }}</td>
                        </tr>
                        <tr>
                            <th>Sisa Pinjaman</th>
                            <td>Rp {{number_format($peminjaman->pokok_sisa)}}</td>
                        </tr>
                        <tr>
                            <th>Bunga (%)</th>
                            <td>{{ $peminjaman->bunga_persen }}%</td>
                        </tr>
                        <tr>
                            <th>Tanggal Peminjaman</th>
                            <td>{{ $peminjaman->tgl_peminjaman }}</td>
                        </tr>
                        <tr>
                            <th>Jatuh Tempo</th>
                            <td>{{ $peminjaman->tgl_jatuh_tempo }}</td>
                        </tr>
                        <tr>
                            <th>Kualitas Kredit</th>
                            <td>
                                @if ($peminjaman->kualitas_kredit == 'Lancar')
                                    <span class="badge bg-success">Lancar</span>
                                @elseif ($peminjaman->kualitas_kredit == 'Kurang Lancar')
                                    <span class="badge bg-warning">Kurang Lancar</span>
                                @elseif ($peminjaman->kualitas_kredit == 'Macet')
                                    <span class="badge bg-danger">Macet</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Diketahui</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Jaminan</th>
                            <td>
                                @if ($peminjaman->jaminan)
                                    <a href="{{ asset('storage/' . $peminjaman->jaminan) }}" target="_blank">
                                        Lihat Jaminan
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>
    </div>
@endsection
