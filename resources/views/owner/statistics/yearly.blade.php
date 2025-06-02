@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('owner.statistics.yearly') }}" method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Pilih Bulan</label>
                            <select name="month" class="form-control" onchange="this.form.submit()">
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create(2024, $month)->isoFormat('MMMM') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pilih Tahun</label>
                            <select name="year" class="form-control" onchange="this.form.submit()">
                                @foreach(range(date('Y'), 2020) as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-primary">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-money-bill fa-lg opacity-10 text-primary"></i>
                    </div>
                    <h3 class="text-white mb-1">Rp {{ number_format($income, 0, ',', '.') }}</h3>
                    <p class="text-sm mb-0 text-white">Pendapatan Bulan {{ \Carbon\Carbon::create(2024, $selectedMonth)->isoFormat('MMMM') }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-success">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-money-check-alt fa-lg opacity-10 text-success"></i>
                    </div>
                    <h3 class="text-white mb-1">Rp {{ number_format($totalYearlyIncome, 0, ',', '.') }}</h3>
                    <p class="text-sm mb-0 text-white">Total Pendapatan Tahun {{ $selectedYear }}</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body px-3 py-3 text-center bg-gradient-info">
                    <div class="icon icon-shape bg-white shadow text-center border-radius-md mb-2 mx-auto">
                        <i class="fas fa-bus fa-lg opacity-10 text-info"></i>
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
                    <h6>Statistik Per Bulan Tahun {{ $selectedYear }}</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="chart p-3">
                        <canvas id="monthlyChart" class="chart-canvas" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const monthlyData = @json($monthlyStats);
    const monthlyIncomes = @json($monthlyIncomes);
    const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: monthNames,
            datasets: [
                {
                    label: 'Jumlah Penyewaan',
                    data: monthNames.map((_, index) => {
                        const monthData = monthlyData.find(d => d.month === index + 1);
                        return monthData ? monthData.total : 0;
                    }),
                    backgroundColor: '#5e72e4',
                    borderRadius: 5,
                    yAxisID: 'y'
                },
                {
                    label: 'Pendapatan (dalam Juta Rupiah)',
                    data: monthNames.map((_, index) => {
                        return (monthlyIncomes[index + 1] || 0) / 1000000;
                    }),
                    backgroundColor: '#2dce89',
                    borderRadius: 5,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
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
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    grid: {
                        display: false
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