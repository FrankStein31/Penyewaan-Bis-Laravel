@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Selamat Datang di Dashboard Anda'])
    <div class="container-fluid py-4">
        <!-- Penyewaan Aktif -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Penyewaan Aktif</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($activeRental)
                        <div class="px-4 py-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-2">Kode Sewa: {{ $activeRental->rental_code }}</h6>
                                    <p class="text-sm mb-1">Bis: {{ $activeRental->bus->plate_number }}</p>
                                    <p class="text-sm mb-1">Supir: {{ $activeRental->driver->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="text-sm mb-1">Mulai: {{ $activeRental->start_date->format('d/m/Y') }}</p>
                                    <p class="text-sm mb-1">Selesai: {{ $activeRental->end_date->format('d/m/Y') }}</p>
                                    <p class="text-sm mb-1">Status: 
                                        <span class="badge badge-sm bg-gradient-success">{{ $activeRental->status }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <p class="text-sm mb-3">Belum ada penyewaan aktif</p>
                            <a href="{{ route('customer.search') }}" class="btn btn-sm bg-gradient-primary">
                                Sewa Bis Sekarang
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Status Pembayaran</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($activeRental && $activeRental->payment)
                        <div class="text-center py-3">
                            <h3 class="mb-2">Rp {{ number_format($activeRental->total_price) }}</h3>
                            <p class="text-sm mb-3">Status: 
                                <span class="badge badge-sm bg-gradient-{{ 
                                    $activeRental->payment_status == 'paid' ? 'success' : 
                                    ($activeRental->payment_status == 'partial' ? 'warning' : 'danger') 
                                }}">
                                    {{ $activeRental->payment_status }}
                                </span>
                            </p>
                            @if($activeRental->payment_status != 'paid')
                            <a href="{{ route('customer.payments.create') }}" class="btn btn-sm bg-gradient-primary">
                                Bayar Sekarang
                            </a>
                            @endif
                        </div>
                        @else
                        <p class="text-center py-4 text-sm">Tidak ada pembayaran yang perlu dilakukan</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Penyewaan -->
        <div class="card">
            <div class="card-header pb-0">
                <h6>Riwayat Penyewaan</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Sewa</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bis</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rentalHistory as $rental)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $rental->rental_code }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $rental->bus->plate_number }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $rental->start_date->format('d/m/Y') }}</p>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">Rp {{ number_format($rental->total_price) }}</p>
                                </td>
                                <td>
                                    <span class="badge badge-sm bg-gradient-{{ 
                                        $rental->status == 'pending' ? 'warning' :
                                        ($rental->status == 'aktif' ? 'info' :
                                        ($rental->status == 'selesai' ? 'success' : 'danger'))
                                    }}">
                                        {{ ucfirst($rental->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($rental->status == 'selesai' && !$rental->hasRating)
                                    <a href="{{ route('customer.ratings.create', $rental->id) }}" 
                                       class="btn btn-sm bg-gradient-warning">
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
                                <td colspan="6" class="text-center py-4">Belum ada riwayat penyewaan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection