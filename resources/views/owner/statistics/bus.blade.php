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
                    <h3 class="text-white mb-1">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                    <p class="text-sm mb-0 text-white">Total Pendapatan</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-success">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-bus fa-lg opacity-10 text-success"></i>
                    </div>
                    <h3 class="text-white mb-1">{{ $totalRentals }}</h3>
                    <p class="text-sm mb-0 text-white">Total Penyewaan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Statistik Penggunaan Bus</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. Polisi</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total Sewa</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($busStats as $bus)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $bus->plate_number }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-success">{{ $bus->rentals_count }} kali</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-gradient-primary">Rp {{ number_format($bus->rentals_sum_total_price, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h6>Distribusi Tipe Bus</h6>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="busTypeChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const busTypeData = @json($usageByType);
    
    new Chart(document.getElementById('busTypeChart'), {
        type: 'pie',
        data: {
            labels: busTypeData.map(d => d.type === 'long' ? 'Bus Besar' : 'Bus Kecil'),
            datasets: [{
                data: busTypeData.map(d => d.total),
                backgroundColor: ['#5e72e4', '#2dce89']
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