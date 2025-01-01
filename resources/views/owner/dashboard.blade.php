@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<style>
.dashboard-card {
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
    background: white;
    transition: transform 0.2s;
}

.dashboard-card:hover {
    transform: translateY(-5px);
}

.icon-container {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.card-content {
    display: flex;
    align-items: center;
    padding: 1rem;
    gap: 1rem;
}

.stats-info {
    flex: 1;
}

.stats-title {
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    color: #8898aa;
    margin-bottom: 0.5rem;
}

.stats-value {
    font-size: 1.25rem;
    font-weight: 700;
    margin: 0;
    color: #32325d;
}
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="stats-info">
                        <p class="stats-title">Total Armada</p>
                        <h5 class="stats-value">{{ $totalBis }} Unit</h5>
                    </div>
                    <div class="icon-container bg-gradient-primary">
                        <i class="fas fa-bus text-white" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="stats-info">
                        <p class="stats-title">Bis Tersedia</p>
                        <h5 class="stats-value">{{ $bisTersedia }} Unit</h5>
                    </div>
                    <div class="icon-container bg-gradient-success">
                        <i class="fas fa-check text-white" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="stats-info">
                        <p class="stats-title">Bis Disewa</p>
                        <h5 class="stats-value">{{ $bisDisewa }} Unit</h5>
                    </div>
                    <div class="icon-container bg-gradient-warning">
                        <i class="fas fa-truck text-white" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="dashboard-card">
                <div class="card-content">
                    <div class="stats-info">
                        <p class="stats-title">Pendapatan Bulan Ini</p>
                        <h5 class="stats-value">Rp {{ number_format($pendapatanBulan) }}</h5>
                    </div>
                    <div class="icon-container bg-gradient-danger">
                        <i class="fas fa-coins text-white" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8 mb-lg-0 mb-4">
            <div class="card z-index-2 h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Statistik Penyewaan</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="rentalChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header pb-0 pt-3 bg-transparent">
                    <h6 class="text-capitalize">Top 5 Armada Favorit</h6>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="busChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Top 5 Supir (Berdasarkan Rating)</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Supir</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Trip</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rating</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topDrivers as $driver)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $driver->name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $driver->total_trips }}</p>
                                    </td>
                                    <td>
                                        <div class="text-warning">
                                            @for($i = 0; $i < 5; $i++)
                                                @if($i < floor($driver->rating))
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $driver->status == 'available' ? 'success' : 'warning' }}">
                                            {{ $driver->status }}
                                        </span>
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

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Penyewaan
    const rentalCtx = document.getElementById('rentalChart').getContext('2d');
    new Chart(rentalCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{
                label: 'Penyewaan',
                data: {!! json_encode($chartData['data']) !!},
                borderColor: '#5e72e4',
                tension: 0.4,
                borderWidth: 2,
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
                        display: true,
                        padding: 10
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        display: true,
                        padding: 10
                    }
                }
            }
        }
    });

    // Grafik Armada Favorit
    const busCtx = document.getElementById('busChart').getContext('2d');
    new Chart(busCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($topBuses['labels']) !!},
            datasets: [{
                data: {!! json_encode($topBuses['data']) !!},
                backgroundColor: ['#5e72e4', '#2dce89', '#fb6340', '#11cdef', '#f5365c'],
                cutout: '70%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush
@endsection