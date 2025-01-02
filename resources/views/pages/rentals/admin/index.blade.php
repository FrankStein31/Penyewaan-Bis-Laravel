@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Data Penyewaan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                        <span class="alert-icon"><i class="fas fa-check"></i></span>
                        <span class="alert-text">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Data Penyewaan</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Sewa</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelanggan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bus</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Crew</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Lokasi</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Total</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembayaran</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rentals as $rental)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $rental->rental_code }}</h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ $rental->created_at->format('d/m/Y H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $rental->user->firstname }} {{ $rental->user->lastname }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">{{ $rental->user->email }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $rental->user->phone }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $rental->bus->name }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $rental->bus->number_plate }}</p>
                                            <p class="text-xs text-secondary mb-0">Kapasitas: {{ $rental->bus->capacity }} Seat</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Driver: 
                                                @if($rental->driver)
                                                    <div class="d-flex align-items-center">
                                                        @if($rental->driver->photo)
                                                            <img src="{{ Storage::url('drivers/'.$rental->driver->photo) }}" 
                                                                 class="avatar avatar-xs rounded-circle me-2" 
                                                                 alt="Driver Photo">
                                                        @endif
                                                        <div>
                                                            <span>{{ $rental->driver->name }}</span>
                                                            <br>
                                                            <small class="text-secondary">HP: {{ $rental->driver->phone }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-secondary">Tidak ada</span>
                                                @endif
                                            </p>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Kondektur: 
                                                @if($rental->conductor)
                                                    <div class="d-flex align-items-center">
                                                        @if($rental->conductor->photo)
                                                            <img src="{{ asset('storage/conductors/' . $rental->conductor->photo) }}" 
                                                                 class="avatar avatar-xs rounded-circle me-2" 
                                                                 alt="Conductor Photo">
                                                        @endif
                                                        <div>
                                                            <span>{{ $rental->conductor->name }}</span>
                                                            <br>
                                                            <small class="text-secondary">HP: {{ $rental->conductor->phone }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-secondary">Tidak ada</span>
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Dari: {{ $rental->pickup_location }}
                                            </p>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Ke: {{ $rental->destination }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ date('d/m/Y', strtotime($rental->start_date)) }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                s/d {{ date('d/m/Y', strtotime($rental->end_date)) }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                ({{ $rental->total_days }} hari)
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">
                                                @if($rental->payment)
                                                    DP: Rp {{ number_format($rental->payment->down_payment, 0, ',', '.') }}
                                                @endif
                                            </p>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $rental->rental_status === 'pending' ? 'warning' : 
                                                ($rental->rental_status === 'confirmed' ? 'info' :
                                                ($rental->rental_status === 'ongoing' ? 'primary' :
                                                ($rental->rental_status === 'completed' ? 'success' : 'danger'))) 
                                            }}">
                                                {{ ucfirst($rental->rental_status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $rental->payment_status == 'unpaid' ? 'warning' : 
                                                ($rental->payment_status == 'partially_paid' ? 'info' : 
                                                ($rental->payment_status == 'paid' ? 'success' : 
                                                ($rental->payment_status == 'cancelled' ? 'danger' : 'secondary'))) 
                                            }}">
                                                {{ ucfirst(str_replace('_', ' ', $rental->payment_status)) }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('admin.rentals.show', $rental) }}" 
                                               class="btn btn-sm bg-gradient-info text-white px-3 mb-0">
                                                <i class="fas fa-eye me-2"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
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