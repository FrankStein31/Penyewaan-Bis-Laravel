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
                    <p class="text-sm mb-0 text-white">Pendapatan Hari Ini</p>
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
                    <h6>Statistik Per Jam</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="chart p-3">
                        <canvas id="hourlyChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const hourlyData = @json($hourlyStats);
    
    new Chart(document.getElementById('hourlyChart'), {
        type: 'bar',
        data: {
            labels: hourlyData.map(d => d.hour + ':00'),
            datasets: [{
                label: 'Jumlah Penyewaan per Jam',
                data: hourlyData.map(d => d.total),
                backgroundColor: '#5e72e4',
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection