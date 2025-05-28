@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6>Detail Penyewaan</h6>
                            <p class="text-sm mb-0">Kode Booking: {{ $rental->rental_code }}</p>
                            <p class="text-sm mb-0">Tanggal Pemesanan: {{ $rental->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <a href="{{ route('owner.rentals.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informasi Utama -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Kode Booking</label>
                                <p class="font-weight-bold">{{ $rental->rental_code }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Status Pesanan</label>
                                <p>
                                    <span class="badge badge-sm bg-gradient-{{ 
                                        $rental->rental_status === 'pending' ? 'warning' : 
                                        ($rental->rental_status === 'confirmed' ? 'info' :
                                        ($rental->rental_status === 'ongoing' ? 'primary' :
                                        ($rental->rental_status === 'completed' ? 'success' : 'danger'))) 
                                    }}">
                                        {{ 
                                            $rental->rental_status === 'pending' ? 'Menunggu Konfirmasi' :
                                            ($rental->rental_status === 'confirmed' ? 'Dikonfirmasi' :
                                            ($rental->rental_status === 'ongoing' ? 'Sedang Berlangsung' :
                                            ($rental->rental_status === 'completed' ? 'Selesai' : 'Dibatalkan')))
                                        }}
                                    </span>
                                </p>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Status Pembayaran</label>
                                <p>
                                    <span class="badge badge-sm bg-gradient-{{ 
                                        $rental->payment_status === 'unpaid' ? 'danger' :
                                        ($rental->payment_status === 'partial' ? 'warning' : 'success')
                                    }}">
                                        {{ 
                                            $rental->payment_status === 'unpaid' ? 'Belum Dibayar' :
                                            ($rental->payment_status === 'partial' ? 'Pembayaran Sebagian' : 'Lunas')
                                        }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Customer</label>
                                <p class="font-weight-bold">{{ $rental->user->name }}</p>
                                <p class="text-sm mb-0">{{ $rental->user->email }}</p>
                                <p class="text-sm">{{ $rental->user->phone }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Tanggal Pemesanan</label>
                                <p class="font-weight-bold">{{ $rental->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Bus dan Crew -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Informasi Bus</label>
                                <p class="font-weight-bold mb-1">{{ $rental->bus->plate_number }}</p>
                                <p class="text-sm mb-1">{{ $rental->bus->armada->nama_armada }}</p>
                                <p class="text-sm">Kapasitas: {{ $rental->bus->capacity }} Seat</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Crew</label>
                                <p class="font-weight-bold mb-1">Driver: {{ $rental->driver->name ?? 'Belum ditentukan' }}</p>
                                <p class="text-sm mb-1">{{ $rental->driver->phone ?? '' }}</p>
                                <p class="font-weight-bold mb-1">Kondektur: {{ $rental->conductor->name ?? 'Belum ditentukan' }}</p>
                                <p class="text-sm">{{ $rental->conductor->phone ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Perjalanan -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Lokasi Jemput</label>
                                <p class="font-weight-bold">{{ $rental->pickup_location }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Tujuan</label>
                                <p class="font-weight-bold">{{ $rental->destination }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Tanggal Sewa</label>
                                <p class="font-weight-bold">{{ $rental->start_date->format('d/m/Y') }} - {{ $rental->end_date->format('d/m/Y') }}</p>
                                <p class="text-sm">({{ $rental->total_days }} hari)</p>
                                <p class="text-sm mb-0">Waktu Ambil Armada:</p>
                                <p class="font-weight-bold">{{ $rental->start_date->subHours(2)->format('d/m/Y H:i') }}</p>
                                <p class="text-sm mb-0">Waktu Kembali Armada:</p>
                                <p class="font-weight-bold">{{ $rental->end_date->addHours(2)->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label">Total Harga</label>
                                <p class="font-weight-bold">Rp {{ number_format($rental->total_price) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Pembayaran -->
                    @if($rental->payments->isNotEmpty())
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">Riwayat Pembayaran</h6>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Pembayaran</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jumlah</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Metode</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rental->payments as $payment)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $payment->payment_code }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($payment->amount) }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ ucfirst($payment->payment_method) }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $payment->status == 'success' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection