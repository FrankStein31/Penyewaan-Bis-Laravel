@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-primary">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-money-bill fa-lg opacity-10 text-primary"></i>
                    </div>
                    <h3 class="text-white mb-1">Rp {{ number_format($income, 0, ',', '.') }}</h3>
                    <p class="text-sm mb-0 text-white">Pendapatan Bulan Ini</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-success">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-bus fa-lg opacity-10 text-success"></i>
                    </div>
                    <h3 class="text-white mb-1">{{ $rentals }}</h3>
                    <p class="text-sm mb-0 text-white">Total Penyewaan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Statistik Per Hari</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="chart p-3">
                        <canvas id="dailyChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dailyData = @json($dailyStats);
    
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: dailyData.map(d => d.date),
            datasets: [{
                label: 'Jumlah Penyewaan per Hari',
                data: dailyData.map(d => d.total),
                borderColor: '#5e72e4',
                backgroundColor: 'rgba(94, 114, 228, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: '#5e72e4',
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
                        stepSize: 1
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
</script>
@endpush
@endsection