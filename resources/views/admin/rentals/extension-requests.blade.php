@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pengajuan Perpanjangan'])
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
                        <h6>Daftar Pengajuan Perpanjangan</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Sewa</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Pelanggan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bus</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Periode Perpanjangan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tambahan Hari</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Biaya</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($extensions as $extension)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $extension->rental->rental_code }}</h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ $extension->created_at->format('d/m/Y H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $extension->rental->user->firstname }} {{ $extension->rental->user->lastname }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">{{ $extension->rental->user->email }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $extension->rental->user->phone }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $extension->rental->bus->type == 'long' ? 'Long (63)' : 'Short (33)' }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $extension->rental->bus->plate_number }}</p>
                                            <p class="text-xs text-secondary mb-0">Kapasitas: {{ $extension->rental->bus->capacity }} Seat</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $extension->start_date->format('d/m/Y') }} - 
                                                {{ $extension->end_date->format('d/m/Y') }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $extension->additional_days }} hari</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Rp {{ number_format($extension->additional_price, 0, ',', '.') }}
                                            </p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $extension->status === 'pending' ? 'warning' : 
                                                ($extension->status === 'approved' ? 'success' : 'danger') 
                                            }}">
                                                {{ $extension->status === 'pending' ? 'Menunggu' :
                                                ($extension->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                                            </span>
                                            @if($extension->status === 'approved')
                                                <br>
                                                <span class="badge badge-sm bg-gradient-{{ 
                                                    $extension->payment_status === 'pending' ? 'warning' : 
                                                    ($extension->payment_status === 'paid' ? 'success' : 'danger') 
                                                }}">
                                                    {{ $extension->payment_status === 'pending' ? 'Belum Dibayar' :
                                                    ($extension->payment_status === 'paid' ? 'Sudah Dibayar' : 'Pembayaran Ditolak') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($extension->status === 'pending')
                                                <form action="{{ route('admin.rentals.approve-extension', $extension) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm mb-2">
                                                        <i class="fas fa-check me-2"></i>Setujui
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.rentals.reject-extension', $extension) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm mb-2">
                                                        <i class="fas fa-times me-2"></i>Tolak
                                                    </button>
                                                </form>
                                            @elseif($extension->status === 'approved' && $extension->payment_status === 'pending')
                                                <form action="{{ route('admin.rentals.verify-extension-payment', $extension) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm mb-2">
                                                        <i class="fas fa-check-double me-2"></i>Verifikasi
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.rentals.reject-extension-payment', $extension) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm mb-2">
                                                        <i class="fas fa-ban me-2"></i>Tolak
                                                    </button>
                                                </form>
                                            @endif
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