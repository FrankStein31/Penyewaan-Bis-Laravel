@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Riwayat Sewa'])
    <style>
        .icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }
        
        .table td {
            vertical-align: middle !important;
        }
        
        .table td > div {
            min-height: 40px;
            display: flex;
            align-items: center;
        }
    </style>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header pb-0 bg-white">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0 font-weight-bold text-lg">Daftar Pesanan Saya</h6>
                                <p class="text-sm text-muted mb-0">Kelola semua pesanan sewa bus Anda di sini</p>
                            </div>
                            <div class="col">
                                <form action="{{ route('customer.rentals.index') }}" method="GET" class="d-flex gap-2">
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari kode booking..." value="{{ request('search') }}">
                                    <select name="bus_type" class="form-select form-select-sm">
                                        <option value="">Semua Tipe Bus</option>
                                        <option value="long" {{ request('bus_type') == 'long' ? 'selected' : '' }}>Long (63)</option>
                                        <option value="short" {{ request('bus_type') == 'short' ? 'selected' : '' }}>Short (33)</option>
                                    </select>
                                    <select name="status" class="form-select form-select-sm">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Sedang Berjalan</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        Cari
                                    </button>
                                    @if(request()->has('search') || request()->has('bus_type') || request()->has('status'))
                                        <a href="{{ route('customer.rentals.index') }}" class="btn btn-sm btn-danger">
                                            Reset
                                        </a>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-3 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Kode Pesanan</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Bus</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Rute</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Periode</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Total</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Status Pesanan</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Pembayaran</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Status Pembayaran</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rentals as $rental)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">#{{ $rental->rental_code }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $rental->created_at->format('d/m/Y H:i') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    @if($rental->bus->photo)
                                                        <img src="{{ Storage::url($rental->bus->photo) }}" 
                                                             class="avatar avatar-sm rounded-circle me-2">
                                                    @else
                                                        <div class="icon-wrapper bg-gradient-primary text-white me-2">
                                                            <i class="fas fa-bus"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="text-sm font-weight-bold mb-0">{{ $rental->bus->plate_number }}</p>
                                                        <p class="text-xs text-secondary mb-0">Type: {{ $rental->bus->type }}</p>
                                                        <p class="text-xs text-secondary mb-0">Kapasitas: {{ $rental->bus->capacity }} Seat</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-3">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Dari: {{ $rental->pickup_location }}
                                                </p>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Ke: {{ $rental->destination }}
                                                </p>
                                            </td>
                                            <td class="ps-3">
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
                                            <td class="ps-3">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                                                </p>
                                                <p class="text-xs text-secondary mb-0">
                                                    @if($rental->payment)
                                                        DP: Rp {{ number_format($rental->payment->down_payment, 0, ',', '.') }}
                                                    @endif
                                                </p>
                                            </td>
                                            <td class="ps-3">
                                                <span class="badge badge-sm bg-gradient-{{ 
                                                    $rental->rental_status === 'pending' ? 'warning' : 
                                                    ($rental->rental_status === 'confirmed' ? 'info' :
                                                    ($rental->rental_status === 'ongoing' ? 'primary' :
                                                    ($rental->rental_status === 'completed' ? 'success' : 'danger'))) 
                                                }}">
                                                    {{ 
                                                        $rental->rental_status === 'pending' ? 'Menunggu Konfrimasi' :
                                                        ($rental->rental_status === 'confirmed' ? 'Dikonfirmasi' :
                                                        ($rental->rental_status === 'ongoing' ? 'Sedang Berjalan' :
                                                        ($rental->rental_status === 'completed' ? 'Selesai' : 'Dibatalkan')))
                                                    }}
                                                </span>
                                            </td>
                                            <td class="ps-3">
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
                                            <td class="ps-3">
                                                <span class="badge badge-sm bg-gradient-{{ 
                                                    $rental->payments && $rental->payments->first() ? 
                                                        ($rental->payments->first()->status == 'pending' ? 'warning' : 
                                                        ($rental->payments->first()->status == 'success' ? 'success' : 
                                                        ($rental->payments->first()->status == 'failed' ? 'danger' : 'secondary')))
                                                    : 'secondary'
                                                }}">
                                                    {{ 
                                                        $rental->payments && $rental->payments->first() ?
                                                            ($rental->payments->first()->status == 'pending' ? 'Menunggu Konfirmasi' :
                                                            ($rental->payments->first()->status == 'success' ? 'Pembayaran Berhasil' :
                                                            ($rental->payments->first()->status == 'failed' ? 'Pembayaran Gagal' : 'Belum Dibayar')))
                                                        : 'Belum Dibayar'
                                                    }}
                                                </span>
                                            </td>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ route('customer.rentals.show', $rental) }}" 
                                                       class="btn btn-sm bg-gradient-info text-white px-3">
                                                        <i class="fas fa-eye me-2"></i> Detail
                                                    </a>
                                                    @if($rental->rental_status === 'pending')
                                                        <form action="{{ route('customer.rentals.cancel', $rental) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-sm bg-gradient-danger text-white px-3"
                                                                    onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                                                <i class="fas fa-times me-2"></i> Batalkan
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-inbox fa-3x text-secondary mb-3"></i>
                                                    <p class="text-secondary font-weight-bold mb-0">Belum ada riwayat pesanan</p>
                                                    <p class="text-xs text-muted">Pesanan Anda akan muncul di sini setelah Anda melakukan pemesanan</p>
                                                </div>
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
    </div>
@endsection