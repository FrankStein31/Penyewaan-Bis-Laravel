@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Dashboard Admin</h2>

    <!-- Status Bis -->
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card card-total-bis">
                <h3>{{ $totalBis }} Unit</h3>
                <p>Total Bis</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card card-tersedia">
                <h3>{{ $bisTersedia }} Unit</h3>
                <p>Bis Tersedia</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card card-disewa">
                <h3>{{ $bisDisewa }} Unit</h3>
                <p>Bis Disewa</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card card-maintenance">
                <h3>{{ $bisMaintenance }} Unit</h3>
                <p>Maintenance</p>
            </div>
        </div>
    </div>

    <!-- Permintaan Penyewaan Terbaru -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Permintaan Penyewaan Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Sewa</th>
                            <th>Penyewa</th>
                            <th>Bis</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestRentals as $rental)
                        <tr>
                            <td>{{ $rental->rental_code }}</td>
                            <td>{{ $rental->user->firstname }}</td>
                            <td>{{ $rental->bus->plate_number }}</td>
                            <td>{{ $rental->start_date->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $rental->status == 'pending' ? 'warning' : 
                                    ($rental->status == 'aktif' ? 'success' : 
                                    ($rental->status == 'selesai' ? 'info' : 'danger')) 
                                }}">
                                    {{ $rental->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.rentals.show', $rental->id) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada permintaan penyewaan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pembayaran yang Perlu Diverifikasi -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Pembayaran yang Perlu Diverifikasi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode Bayar</th>
                            <th>Penyewa</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPayments as $payment)
                        <tr>
                            <td>{{ $payment->payment_code }}</td>
                            <td>{{ $payment->rental->user->firstname }}</td>
                            <td>Rp {{ number_format($payment->amount) }}</td>
                            <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">
                                    <i class="fas fa-image"></i>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.payments.verify', $payment->id) }}" 
                                   class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pembayaran yang perlu diverifikasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 