@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Daftar Pemesanan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                        <span class="alert-icon"><i class="ni ni-check-bold"></i></span>
                        <span class="alert-text">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Daftar Pemesanan Saya</h6>
                            <a href="{{ route('buses.search') }}" class="btn bg-gradient-primary btn-sm ms-auto">
                                <i class="fas fa-plus"></i> Pesan Bus
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bus</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Armada</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal Sewa</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tujuan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @if($booking->bus->image)
                                                        <img src="{{ asset('img/buses/' . $booking->bus->image) }}" 
                                                             class="avatar avatar-sm me-3" alt="bus">
                                                    @else
                                                        <img src="{{ asset('img/bus-placeholder.png') }}" 
                                                             class="avatar avatar-sm me-3" alt="bus">
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $booking->bus->plate_number }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ ucfirst($booking->bus->type) }}</p>
                                                    <p class="text-xs text-secondary mb-0">
                                                        <span class="badge badge-sm bg-gradient-info">
                                                            {{ $booking->bus->capacity }} Kursi
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $booking->bus->armada->name }}</p>
                                            <p class="text-xs text-secondary mb-0">
                                                <span class="badge badge-sm bg-gradient-primary">
                                                    {{ $booking->package_type }} 
                                                </span>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">{{ $booking->total_days }} hari</p>
                                            <p class="text-xs text-secondary mb-0">
                                                <span class="badge badge-sm bg-gradient-warning">
                                                    {{ $booking->trip_type }}
                                                </span>
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $booking->destination }}</p>
                                            <p class="text-xs text-secondary mb-0">Dari: {{ $booking->pickup_location }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                            </p>
                                            @if($booking->down_payment)
                                            <p class="text-xs text-secondary mb-0">
                                                DP: Rp {{ number_format($booking->down_payment, 0, ',', '.') }}
                                            </p>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @php
                                                $statusClass = [
                                                    'pending' => 'bg-gradient-warning',
                                                    'confirmed' => 'bg-gradient-success', 
                                                    'cancelled' => 'bg-gradient-danger',
                                                    'completed' => 'bg-gradient-info'
                                                ][$booking->status];

                                                $statusText = [
                                                    'pending' => 'Menunggu',
                                                    'confirmed' => 'Dikonfirmasi',
                                                    'cancelled' => 'Dibatalkan', 
                                                    'completed' => 'Selesai'
                                                ][$booking->status];
                                            @endphp
                                            <span class="badge badge-sm {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('bookings.show', $booking) }}" 
                                               class="btn btn-sm bg-gradient-info text-white px-3 mb-0">
                                                <i class="fas fa-eye me-2"></i> Detail
                                            </a>
                                            @if($booking->status === 'pending')
                                                <form action="{{ route('bookings.cancel', $booking) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm bg-gradient-danger text-white px-3 mb-0"
                                                            onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                                        <i class="fas fa-times me-2"></i> Batalkan
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <p class="text-sm mb-0">Belum ada pemesanan</p>
                                            <a href="{{ route('buses.search') }}" class="btn btn-sm btn-primary mt-3">
                                                Pesan Bus Sekarang
                                            </a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 