@extends('layouts.app')

@push('css')
<style>
    .icon-shape {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
    }
    
    .icon-shape i {
        font-size: 1.25rem;
        line-height: 1;
    }

    .table .icon-shape {
        width: 36px;
        height: 36px;
    }

    .border-radius-md {
        border-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Statistik Utama -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-primary">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-building fa-lg opacity-10 text-primary"></i>
                    </div>
                    <h3 class="text-white mb-1">{{ $totalArmada }}</h3>
                    <p class="text-sm mb-0 text-white">Total Armada</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-success">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-bus fa-lg opacity-10 text-success"></i>
                    </div>
                    <h3 class="text-white mb-1">{{ $totalBis }} Unit</h3>
                    <p class="text-sm mb-0 text-white">{{ $bisTersedia }} tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-warning">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-coins fa-lg opacity-10 text-warning"></i>
                    </div>
                    <h3 class="text-white mb-1">Rp {{ number_format($pendapatanBulan) }}</h3>
                    <p class="text-sm mb-0 text-white">Pendapatan Bulan Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-danger">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-chart-line fa-lg opacity-10 text-danger"></i>
                    </div>
                    <h3 class="text-white mb-1">Rp {{ number_format($pendapatanTahun) }}</h3>
                    <p class="text-sm mb-0 text-white">Pendapatan Tahun Ini</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Armada -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Grafik Pendapatan 12 Bulan Terakhir</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="revenueChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Statistik Armada</h6>
                </div>
                <div class="card-body p-3">
                    @foreach($statistikArmada as $armada)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ $armada->nama_armada }}</h6>
                            <span class="badge bg-primary">{{ $armada->buses_count }} Bus</span>
                        </div>
                        <div class="progress">
                            <!-- <div class="progress-bar bg-success" style="width: {{ ($armada->buses_tersedia / $armada->buses_count) * 100 }}%" 
                                 title="{{ $armada->buses_tersedia }} Tersedia">
                            </div> -->
                            <div class="progress-bar bg-success" style="width: {{ $armada->buses_count > 0 ? ($armada->buses_tersedia / $armada->buses_count) * 100 : 0 }}%" 
                                title="{{ $armada->buses_tersedia }} Tersedia">
                            </div>
                            <!-- <div class="progress-bar bg-warning" style="width: {{ ($armada->buses_disewa / $armada->buses_count) * 100 }}%"
                                 title="{{ $armada->buses_disewa }} Disewa">
                            </div> -->
                            <div class="progress-bar bg-warning" style="width: {{ $armada->buses_count > 0 ? ($armada->buses_disewa / $armada->buses_count) * 100 : 0 }}%"
                                title="{{ $armada->buses_disewa }} Disewa">
                            </div>
                            <!-- <div class="progress-bar bg-danger" style="width: {{ ($armada->buses_maintenance / $armada->buses_count) * 100 }}%"
                                 title="{{ $armada->buses_maintenance }} Maintenance">
                            </div> -->
                            <div class="progress-bar bg-danger" style="width: {{ $armada->buses_count > 0 ? ($armada->buses_maintenance / $armada->buses_count) * 100 : 0 }}%"
                                title="{{ $armada->buses_maintenance }} Maintenance">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <small class="text-success">{{ $armada->buses_tersedia }} Tersedia</small>
                            <small class="text-warning">{{ $armada->buses_disewa }} Disewa</small>
                            <small class="text-danger">{{ $armada->buses_maintenance }} Maintenance</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Top Favorit -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Bus Terfavorit</h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bus</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Armada</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Sewa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topBuses['labels'] as $index => $plateNumber)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-gradient-primary shadow">
                                                <i class="fas fa-bus text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $plateNumber }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-info">{{ $topBuses['armada'][$index] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-success">{{ $topBuses['data'][$index] }} kali</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Driver Terfavorit</h6>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Driver</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Trip</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDrivers as $driver)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1 align-items-center">
                                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center bg-gradient-info shadow">
                                                <i class="fas fa-user text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $driver->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-info">{{ $driver->rentals_count }} trip</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-warning">{{ number_format($driver->ratings_avg_rating, 1) }} ⭐</span>
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

    <!-- Setelah grafik pendapatan, tambahkan grafik pemesanan -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Grafik Pemesanan 12 Bulan Terakhir</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="bookingChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Pendapatan
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartPendapatan['labels']) !!},
            datasets: [{
                label: 'Pendapatan',
                data: {!! json_encode($chartPendapatan['data']) !!},
                borderColor: '#5e72e4',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(94, 114, 228, 0.1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    }
                }
            }
        }
    });

    // Grafik Pemesanan dengan Line Chart
    new Chart(document.getElementById('bookingChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartPemesanan['labels']) !!},
            datasets: [{
                label: 'Jumlah Pemesanan',
                data: {!! json_encode($chartPemesanan['data']) !!},
                fill: true,
                borderColor: '#2dce89',
                backgroundColor: 'rgba(45, 206, 137, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#2dce89',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverRadius: 6,
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#fff',
                    titleColor: '#000',
                    bodyColor: '#000',
                    borderColor: '#e9ecef',
                    borderWidth: 1,
                    padding: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Pemesanan';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            if (Math.floor(value) === value) {
                                return value;
                            }
                        }
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
</script>
@endpush
@endsection