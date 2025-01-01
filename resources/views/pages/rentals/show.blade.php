@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Pesanan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Detail Pesanan</h6>
                            @if($rental->rental_status === 'pending')
                                <form action="{{ route('customer.rentals.cancel', $rental) }}" method="POST" class="ms-auto">
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
                                <p class="text-sm mb-0">Kode Pesanan:</p>
                                <h6>{{ $rental->rental_code }}</h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-sm mb-0">Status Pesanan:</p>
                                <span class="badge bg-{{ $rental->rental_status === 'pending' ? 'warning' : ($rental->rental_status === 'confirmed' ? 'success' : 'danger') }}">
                                    {{ ucfirst($rental->rental_status) }}
                                </span>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Tanggal Mulai:</p>
                                <h6>{{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y H:i') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Tanggal Selesai:</p>
                                <h6>{{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y H:i') }}</h6>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-sm mb-0">Lokasi Penjemputan:</p>
                                <h6>{{ $rental->pickup_location }}</h6>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-sm mb-0">Tujuan:</p>
                                <h6>{{ $rental->destination }}</h6>
                            </div>
                        </div>
                        @if($rental->notes)
                            <hr class="horizontal dark">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-sm mb-0">Catatan:</p>
                                    <h6>{{ $rental->notes }}</h6>
                                </div>
                            </div>
                        @endif
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Total Hari:</p>
                                <h6>{{ $rental->total_days }} hari</h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-sm mb-0">Total Harga:</p>
                                <h6>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-0">Detail Bus</h6>
                        <hr class="horizontal dark">
                        @if($rental->bus->photo)
                            <img src="{{ asset('storage/' . $rental->bus->photo) }}" alt="Bus Photo" class="img-fluid mb-3 rounded">
                        @endif
                        <h5>{{ $rental->bus->plate_number }}</h5>
                        <p class="mb-2">
                            <span class="badge bg-primary">{{ ucfirst($rental->bus->type) }}</span>
                            <span class="badge bg-info">{{ $rental->bus->capacity }} Kursi</span>
                        </p>
                        <p class="text-sm mb-2">{{ $rental->bus->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-sm">Harga per Hari:</span>
                            <span class="font-weight-bold">
                                Rp {{ number_format($rental->bus->price_per_day, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 