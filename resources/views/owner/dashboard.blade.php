@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard Owner</h2>

    <!-- Statistik Utama -->
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card card-total-bis">
                <h3>{{ $totalBis }} Unit</h3>
                <p>Total Armada</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card card-tersedia">
                <h3>{{ $bisTersedia }} Unit</h3>
                <p>Bis Tersedia</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card card-disewa">
                <h3>{{ $bisDisewa }} Unit</h3>
                <p>Bis Disewa</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card" style="background: #6f42c1">
                <h3>Rp {{ number_format($pendapatanBulan) }}</h3>
                <p>Pendapatan Bulan Ini</p>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Statistik Penyewaan</h5>
                </div>
                <div class="card-body">
                    <canvas id="rentalChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top 5 Armada Favorit</h5>
                </div>
                <div class="card-body">
                    <canvas id="busChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Supir Terbaik -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Top 5 Supir (Berdasarkan Rating)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Supir</th>
                            <th>Total Trip</th>
                            <th>Rating</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topDrivers as $driver)
                        <tr>
                            <td>{{ $driver->name }}</td>
                            <td>{{ $driver->total_trips }}</td>
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
                                <span class="badge badge-{{ $driver->status == 'available' ? 'success' : 'warning' }}">
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

@push('scripts')
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
                tension: 0.1
            }]
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
                backgroundColor: ['#5e72e4', '#2dce89', '#fb6340', '#11cdef', '#f5365c']
            }]
        }
    });
</script>
@endpush
@endsection 