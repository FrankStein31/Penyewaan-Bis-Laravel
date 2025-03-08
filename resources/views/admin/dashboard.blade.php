@extends('layouts.app')

@section('content')
@include('layouts.navbars.auth.topnav', ['title' => 'Dashboard Admin'])
<div class="container-fluid py-4">
    <!-- Status Bis -->
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body px-3 py-3 text-center bg-gradient-info">
                    <h3 class="mb-2 text-white">{{ $totalBis }} Unit</h3>
                    <p class="text-sm mb-0 text-white">Total Bis</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body px-3 py-3 text-center bg-gradient-success">
                    <h3 class="mb-2 text-white">{{ $bisTersedia }} Unit</h3>
                    <p class="text-sm mb-0 text-white">Bis Tersedia</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body px-3 py-3 text-center bg-gradient-warning">
                    <h3 class="mb-2 text-white">{{ $bisDisewa }} Unit</h3>
                    <p class="text-sm mb-0 text-white">Bis Disewa</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mb-4">
                <div class="card-body px-3 py-3 text-center bg-gradient-danger">
                    <h3 class="mb-2 text-white">{{ $bisMaintenance }} Unit</h3>
                    <p class="text-sm mb-0 text-white">Maintenance</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Permintaan Penyewaan Terbaru -->
    <div class="card">
        <div class="card-header pb-0">
            <h6>Permintaan Penyewaan Terbaru</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Sewa</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Penyewa</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bis</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestRentals->where('status', '!=', 'selesai') as $rental)
                        <tr>
                            <td>
                                <p class="text-sm font-weight-bold mb-0 px-3">{{ $rental->rental_code }}</p>
                            </td>
                            <td>
                                <p class="text-sm font-weight-bold mb-0">{{ $rental->user->firstname }}</p>
                            </td>
                            <td>
                                <p class="text-sm font-weight-bold mb-0">{{ $rental->bus->plate_number }}</p>
                            </td>
                            <td>
                                <p class="text-sm font-weight-bold mb-0">{{ $rental->start_date->format('d/m/Y') }}</p>
                            </td>
                            <td>
                                <span class="badge badge-sm bg-gradient-{{ 
                                    $rental->status == 'pending' ? 'warning' : 
                                    ($rental->status == 'aktif' ? 'success' : 
                                    ($rental->status == 'selesai' ? 'info' : 'danger')) 
                                }}">
                                    {{ $rental->status == 'pending' ? 'Menunggu' : $rental->status }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.rentals.show', $rental->id) }}" 
                                   class="btn btn-sm bg-gradient-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Belum ada permintaan penyewaan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pembayaran yang Perlu Diverifikasi -->
    <div class="card mt-4">
        <div class="card-header pb-0">
            <h6>Pembayaran yang Perlu Diverifikasi</h6>
        </div>
        <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Bayar</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Penyewa</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Jumlah</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tanggal</th>
                            <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Bukti</th> -->
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPayments as $payment)
                        <tr>
                            <td>
                                <p class="text-sm font-weight-bold mb-0 px-3">{{ $payment->payment_code }}</p>
                            </td>
                            <td>
                                <p class="text-sm font-weight-bold mb-0">{{ $payment->rental->user->firstname }}</p>
                            </td>
                            <td>
                                <p class="text-sm font-weight-bold mb-0">Rp {{ number_format($payment->amount) }}</p>
                            </td>
                            <td>
                                <p class="text-sm font-weight-bold mb-0">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                            </td>
                            <!-- <td>
                                @if($payment->payment_method === 'transfer' && $payment->proof)
                                    <a href="{{ Storage::url($payment->proof) }}" 
                                       target="_blank" 
                                       class="btn btn-link text-info text-sm mb-0">
                                        <i class="fas fa-file-image me-1"></i>
                                        Lihat Bukti
                                    </a>
                                @else
                                    <span class="text-xs text-secondary">
                                        <a  class="btn btn-link text-info text-sm mb-0">
                                            <i class="fas fa-minus me-1"></i>
                                        </a>
                                    </span>
                                @endif
                            </td> -->
                            <td>
                                @if($payment->status === 'pending')
                                    <form action="{{ route('admin.payments.verify', $payment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Verifikasi</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">Tidak ada pembayaran yang perlu diverifikasi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection