@extends('layouts.app')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard Customer</h2>

    <!-- Penyewaan Aktif -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Penyewaan Aktif</h5>
                </div>
                <div class="card-body">
                    @if($activeRental)
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Kode Sewa: {{ $activeRental->rental_code }}</h6>
                                <p>Bis: {{ $activeRental->bus->plate_number }}</p>
                                <p>Supir: {{ $activeRental->driver->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p>Mulai: {{ $activeRental->start_date->format('d/m/Y') }}</p>
                                <p>Selesai: {{ $activeRental->end_date->format('d/m/Y') }}</p>
                                <p>Status: 
                                    <span class="badge badge-success">{{ $activeRental->status }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <p>Belum ada penyewaan aktif</p>
                        <a href="{{ route('customer.search') }}" class="btn btn-primary">
                            Sewa Bis Sekarang
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Status Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($activeRental && $activeRental->payment)
                    <div class="text-center">
                        <h3>Rp {{ number_format($activeRental->total_price) }}</h3>
                        <p>Status: 
                            <span class="badge badge-{{ 
                                $activeRental->payment_status == 'paid' ? 'success' : 
                                ($activeRental->payment_status == 'partial' ? 'warning' : 'danger') 
                            }}">
                                {{ $activeRental->payment_status }}
                            </span>
                        </p>
                        @if($activeRental->payment_status != 'paid')
                        <a href="{{ route('customer.payments.create') }}" class="btn btn-primary">
                            Bayar Sekarang
                        </a>
                        @endif
                    </div>
                    @else
                    <p class="text-center">Tidak ada pembayaran yang perlu dilakukan</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Penyewaan -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Riwayat Penyewaan</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Sewa</th>
                            <th>Bis</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Rating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentalHistory as $rental)
                        <tr>
                            <td>{{ $rental->rental_code }}</td>
                            <td>{{ $rental->bus->plate_number }}</td>
                            <td>{{ $rental->start_date->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($rental->total_price) }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $rental->status == 'selesai' ? 'success' : 'info' 
                                }}">
                                    {{ $rental->status }}
                                </span>
                            </td>
                            <td>
                                @if($rental->status == 'selesai' && !$rental->hasRating)
                                <a href="{{ route('customer.ratings.create', $rental->id) }}" 
                                   class="btn btn-sm btn-warning">
                                    Beri Rating
                                </a>
                                @elseif($rental->hasRating)
                                <div class="text-warning">
                                    @for($i = 0; $i < 5; $i++)
                                        @if($i < $rental->rating)
                                            <i class="fas fa-star"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada riwayat penyewaan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 