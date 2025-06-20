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
                        <div class="d-flex justify-content-between align-items-center">
                            <h6>Data Penyewaan</h6>
                            <div class="d-flex gap-2">
                                <a href="{{ route('owner.rentals.export') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-file-excel me-2"></i>Export Excel
                                </a>
                                <form action="{{ route('owner.rentals.index') }}" method="GET" class="d-flex gap-2">
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama pelanggan..." value="{{ request('search') }}">
                                    <select name="bus_type" class="form-select form-select-sm" style="width: 250px;">
                                        <option value="">Semua Tipe Bus</option>
                                        <option value="long" {{ request('bus_type') == 'long' ? 'selected' : '' }}>Long (63)</option>
                                        <option value="short" {{ request('bus_type') == 'short' ? 'selected' : '' }}>Short (33)</option>
                                    </select>
                                    <select name="driver" class="form-select form-select-sm" style="width: 250px;">
                                        <option value="">Semua Supir</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ request('driver') == $driver->id ? 'selected' : '' }}>
                                                {{ $driver->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="month" class="form-select form-select-sm" style="width: 250px;">
                                        <option value="">Pilih Bulan</option>
                                        @foreach($months as $key => $month)
                                            <option value="{{ $key }}" {{ request('month') == $key ? 'selected' : '' }}>
                                                {{ $month }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <select name="year" class="form-select form-select-sm" style="width: 250px;">
                                        <option value="">Pilih Tahun</option>
                                        @foreach($years as $year)
                                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Filter
                                    </button>
                                    @if(request()->has('search') || request()->has('bus_type') || request()->has('driver') || request()->has('month') || request()->has('year'))
                                        <a href="{{ route('owner.rentals.index') }}" class="btn btn-sm btn-danger">
                                            Reset
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h5 class="mb-0">
                                        Total Pendapatan: Rp {{ number_format($totalIncome, 0, ',', '.') }}
                                        @if(request('month') && request('year'))
                                            <small>({{ $months[request('month')] }} {{ request('year') }})</small>
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </div>
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
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status Pesanan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pembayaran</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status Pembayaran</th>
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
                                            <p class="text-xs font-weight-bold mb-0">{{ $rental->bus->type == 'long' ? 'Long (63)' : 'Short (33)' }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $rental->bus->plate_number }}</p>
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
                                                {{ 
                                                    $rental->rental_status === 'pending' ? 'Menunggu' :
                                                    ($rental->rental_status === 'confirmed' ? 'Dikonfirmasi' :
                                                    ($rental->rental_status === 'ongoing' ? 'Sedang Berjalan' :
                                                    ($rental->rental_status === 'completed' ? 'Selesai' : 'Dibatalkan')))
                                                }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $rental->payment_status === 'unpaid' ? 'danger' :
                                                ($rental->payment_status === 'partial' ? 'warning' : 'success')
                                            }}">
                                                {{ 
                                                    $rental->payment_status === 'unpaid' ? 'Belum Dibayar' :
                                                    ($rental->payment_status === 'partial' ? 'Pembayaran Sebagian' : 'Lunas')
                                                }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $rental->payments->isNotEmpty() && $rental->payments->first()->status == 'pending' ? 'warning' : 
                                                ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'success' ? 'success' : 
                                                ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'failed' ? 'danger' : 'secondary'))
                                            }}">
                                                {{ 
                                                    $rental->payments->isNotEmpty() && $rental->payments->first()->status == 'pending' ? 'Menunggu Konfirmasi' :
                                                    ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'success' ? 'Pembayaran Berhasil' :
                                                    ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'failed' ? 'Pembayaran Gagal' : 'Belum Dibayar'))
                                                }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('owner.rentals.show', $rental) }}" 
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

@push('js')
<script>
    $(document).ready(function() {
        // Inisialisasi select2 untuk dropdown supir
        $('.form-select').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    });
</script>
@endpush 