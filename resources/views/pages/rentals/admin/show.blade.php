@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Penyewaan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Detail Penyewaan</h6>
                            <a href="{{ route('admin.rentals.index') }}" class="btn btn-sm bg-gradient-secondary ms-auto">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-sm">Informasi Penyewaan</h6>
                                <div class="form-group">
                                    <label class="form-control-label">Kode Sewa</label>
                                    <p class="form-control-static">{{ $rental->rental_code }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal Pemesanan</label>
                                    <p class="form-control-static">{{ $rental->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Status</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-sm bg-gradient-{{ 
                                            $rental->status == 'pending' ? 'warning' : 
                                            ($rental->status == 'confirmed' ? 'success' : 
                                            ($rental->status == 'cancelled' ? 'danger' : 
                                            ($rental->status == 'completed' ? 'info' : 'secondary'))) 
                                        }}">
                                            {{ ucfirst($rental->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-sm">Informasi Pelanggan</h6>
                                <div class="form-group">
                                    <label class="form-control-label">Nama</label>
                                    <p class="form-control-static">{{ $rental->user->firstname }} {{ $rental->user->lastname }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Email</label>
                                    <p class="form-control-static">{{ $rental->user->email }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">No. Telepon</label>
                                    <p class="form-control-static">{{ $rental->user->phone }}</p>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-sm">Informasi Bus</h6>
                                <div class="form-group">
                                    <label class="form-control-label">Nama Bus</label>
                                    <p class="form-control-static">{{ $rental->bus->name }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Nomor Polisi</label>
                                    <p class="form-control-static">{{ $rental->bus->number_plate }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Kapasitas</label>
                                    <p class="form-control-static">{{ $rental->bus->capacity }} Seat</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-sm">Informasi Crew</h6>
                                <div class="form-group">
                                    <label class="form-control-label">Driver</label>
                                    <p class="form-control-static">
                                        @if($rental->driver)
                                            <div class="d-flex align-items-center">
                                                @if($rental->driver->photo)
                                                    <img src="/storage/{{ $rental->driver->photo }}" 
                                                         class="avatar avatar-sm rounded-circle me-2" 
                                                         alt="Driver Photo">
                                                @endif
                                                <div>
                                                    {{ $rental->driver->name }}
                                                    <small class="d-block text-secondary">
                                                        No. HP: {{ $rental->driver->phone }}
                                                    </small>
                                                    <small class="d-block text-secondary">
                                                        SIM: {{ $rental->driver->license_number }}
                                                        (Exp: {{ date('d/m/Y', strtotime($rental->driver->license_expire)) }})
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-secondary">Tidak ada</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Kondektur</label>
                                    <p class="form-control-static">
                                        @if($rental->conductor)
                                            <div class="d-flex align-items-center">
                                                @if($rental->conductor->photo)
                                                    <img src="/storage/{{ $rental->conductor->photo }}" 
                                                         class="avatar avatar-sm rounded-circle me-2" 
                                                         alt="Conductor Photo">
                                                @endif
                                                <div>
                                                    {{ $rental->conductor->name }}
                                                    <small class="d-block text-secondary">
                                                        No. HP: {{ $rental->conductor->phone }}
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-secondary">Tidak ada</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-sm">Informasi Perjalanan</h6>
                                <div class="form-group">
                                    <label class="form-control-label">Lokasi Penjemputan</label>
                                    <p class="form-control-static">{{ $rental->pickup_location }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Tujuan</label>
                                    <p class="form-control-static">{{ $rental->destination }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal Mulai</label>
                                    <p class="form-control-static">{{ date('d/m/Y', strtotime($rental->start_date)) }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Tanggal Selesai</label>
                                    <p class="form-control-static">{{ date('d/m/Y', strtotime($rental->end_date)) }}</p>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Total Hari</label>
                                    <p class="form-control-static">{{ $rental->total_days }} hari</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-sm">Informasi Pembayaran</h6>
                                <div class="form-group">
                                    <label class="form-control-label">Total Harga</label>
                                    <p class="form-control-static">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                                </div>
                                @if($rental->payment)
                                    <div class="form-group">
                                        <label class="form-control-label">DP</label>
                                        <p class="form-control-static">Rp {{ number_format($rental->payment->down_payment, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Sisa Pembayaran</label>
                                        <p class="form-control-static">Rp {{ number_format($rental->total_price - $rental->payment->down_payment, 0, ',', '.') }}</p>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="form-control-label">Status Pembayaran</label>
                                    <p class="form-control-static">
                                        <span class="badge badge-sm bg-gradient-{{ 
                                            $rental->payment_status == 'unpaid' ? 'warning' : 
                                            ($rental->payment_status == 'partially_paid' ? 'info' : 
                                            ($rental->payment_status == 'paid' ? 'success' : 
                                            ($rental->payment_status == 'cancelled' ? 'danger' : 'secondary'))) 
                                        }}">
                                            {{ ucfirst(str_replace('_', ' ', $rental->payment_status)) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 