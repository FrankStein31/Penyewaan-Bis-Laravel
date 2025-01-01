@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Pemesanan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Detail Pemesanan</h6>
                            @if($booking->status === 'pending')
                                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="ms-auto">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                        <i class="fas fa-times"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm mb-1">ID Pemesanan</p>
                                <h6 class="mb-3">#{{ $booking->id }}</h6>

                                <p class="text-sm mb-1">Status</p>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-gradient-warning',
                                        'confirmed' => 'bg-gradient-success',
                                        'cancelled' => 'bg-gradient-danger',
                                        'completed' => 'bg-gradient-info'
                                    ][$booking->status];
                                @endphp
                                <h6 class="mb-3">
                                    <span class="badge {{ $statusClass }}">{{ ucfirst($booking->status) }}</span>
                                </h6>

                                <p class="text-sm mb-1">Tanggal Pemesanan</p>
                                <h6 class="mb-3">{{ $booking->created_at->format('d/m/Y H:i') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm mb-1">Tanggal Sewa</p>
                                <h6 class="mb-3">
                                    {{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}
                                    <small class="text-muted">({{ $booking->total_days }} hari)</small>
                                </h6>

                                <p class="text-sm mb-1">Tujuan</p>
                                <h6 class="mb-3">{{ $booking->destination }}</h6>

                                <p class="text-sm mb-1">Total Pembayaran</p>
                                <h6 class="mb-3">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</h6>
                            </div>
                            @if($booking->notes)
                                <div class="col-12">
                                    <p class="text-sm mb-1">Catatan</p>
                                    <p class="mb-3">{{ $booking->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Detail Bus</h6>
                    </div>
                    <div class="card-body">
                        @if($booking->bus->image)
                            <img src="{{ asset('img/buses/' . $booking->bus->image) }}" 
                                 class="img-fluid rounded mb-3" alt="Bus Image">
                        @else
                            <img src="{{ asset('img/bus-placeholder.png') }}" 
                                 class="img-fluid rounded mb-3" alt="Bus Image">
                        @endif
                        <h5>{{ $booking->bus->plate_number }}</h5>
                        <p class="mb-2">
                            <span class="badge bg-primary">{{ ucfirst($booking->bus->type) }}</span>
                            <span class="badge bg-info">{{ $booking->bus->capacity }} Kursi</span>
                        </p>
                        <p class="text-sm mb-2">{{ $booking->bus->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-sm">Harga per Hari:</span>
                            <span class="font-weight-bold">
                                Rp {{ number_format($booking->bus->price_per_day, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 