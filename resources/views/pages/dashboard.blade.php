@extends('partials.app')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">Welcome {{ Auth::user()->name }}</h3>
                            <h6 class="font-weight-normal mb-0">Dashboard </h6>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button"
                                        id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="true">
                                        <i class="mdi mdi-calendar"></i> Today
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                        <a class="dropdown-item" href="#">January - March</a>
                                        <a class="dropdown-item" href="#">March - June</a>
                                        <a class="dropdown-item" href="#">June - August</a>
                                        <a class="dropdown-item" href="#">August - November</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kotak informasi awal peminjaman --}}
            <div class="row">
                <div class="col-md-4 mb-4 stretch-card transparent">
                    <div class="card card-tale">
                        <div class="card-body">
                            <p class="mb-4">Total Data</p>
                            <p class="fs-30 mb-2">{{ $totalData }}</p>
                        </div>
                        <i class="mdi mdi-database fs-40 opacity-50"></i>
                    </div>
                </div>

                <div class="col-md-4 mb-4 stretch-card transparent">
                    <div class="card card-dark-blue">
                        <div class="card-body">
                            <p class="mb-4">Total Notifikasi</p>
                            <p class="fs-30 mb-2">{{ $totalNotifikasi }}</p>
                        </div>
                        <i class="mdi mdi-bell-ring fs-40 opacity-50"></i>
                    </div>
                </div>

                <div class="col-md-4 mb-4 stretch-card transparent">
                    <div class="card card-light-blue">
                        <div class="card-body">
                            <p class="mb-4">Jatuh Tempo (30 Hari)</p>
                            <p class="fs-30 mb-2">{{ $jatuhTempo30Hari }}</p>
                        </div>
                        <i class="mdi mdi-timer-alert fs-40 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">
                                Grafik Kualitas Kredit Peminjaman
                            </h4>

                            <div class="chart-wrapper">
                                <canvas id="kualitasKreditChart"></canvas>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="mb-3">Jatuh Tempo 30 Hari Ke Depan</h5>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Mitra</th>
                                <th>Jatuh Tempo</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jatuhTempoList as $item)
                                <tr>
                                    <td>{{ $item->nama_mitra }}</td>
                                    <td>{{ $item->tgl_jatuh_tempo }}</td>
                                    <td>
                                        @php
                                            $sisaHari = now()->diffInDays($item->tgl_jatuh_tempo, false);
                                        @endphp

                                        @if ($sisaHari < 0)
                                            <span class="badge bg-danger">Terlambat</span>
                                        @elseif ($sisaHari <= 30)
                                            <span class="badge bg-warning">Segera</span>
                                        @else
                                            <span class="badge bg-success">Aman</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('data.show', $item->id) }}" class="btn btn-sm btn-info">
                                            <i class="mdi mdi-eye"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('partials._footer')
        <!-- partial -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('kualitasKreditChart').getContext('2d');

        new Chart(ctx, {
            type: 'doughnut', // bisa diganti: 'bar'
            data: {
                labels: {!! json_encode($chartData->keys()) !!},
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: {!! json_encode($chartData->values()) !!},
                    backgroundColor: [
                        '#dc3545', // Lancar
                        '#28a745', // Kurang Lancar
                        '#ffc107', // Macet
                        '#6c757d' // fallback
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
@endsection
