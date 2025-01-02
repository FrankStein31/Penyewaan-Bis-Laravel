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
            vertical-align: middle !important; /* Memaksa vertical alignment ke tengah */
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
                        </div>
                    </div>
                    <div class="card-body px-0 pt-3 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Kode Pesanan</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Bus</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Tanggal</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Status</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Total</th>
                                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rentals as $rental)
                                        <tr>
                                            <td class="ps-3">
                                                <div>
                                                    <span class="text-primary font-weight-bold">#{{ $rental->rental_code }}</span>
                                                </div>
                                            </td>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-gradient-primary text-white me-3">
                                                        <i class="fas fa-bus"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-weight-bold mb-0">{{ $rental->bus->plate_number }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-gradient-info text-white me-3">
                                                        <i class="fas fa-calendar"></i>
                                                    </div>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        {{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="ps-3">
                                                <div>
                                                    <span class="badge badge-sm bg-gradient-{{ 
                                                        $rental->rental_status === 'pending' ? 'warning' : 
                                                        ($rental->rental_status === 'confirmed' ? 'info' :
                                                        ($rental->rental_status === 'ongoing' ? 'primary' :
                                                        ($rental->rental_status === 'completed' ? 'success' : 'danger'))) 
                                                    }} px-3 py-2">
                                                        {{ ucfirst($rental->rental_status) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-wrapper bg-gradient-success text-white me-3">
                                                        <i class="fas fa-money-bill"></i>
                                                    </div>
                                                    <p class="text-sm font-weight-bold mb-0">
                                                        Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </td>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <a href="{{ route('customer.rentals.show', $rental) }}" 
                                                       class="btn btn-sm btn-info px-3">
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
                                            <td colspan="6" class="text-center py-5">
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