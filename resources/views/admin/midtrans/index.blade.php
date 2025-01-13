@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Transaksi Midtrans'])
    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <p class="text-sm mb-1 text-uppercase font-weight-bold text-primary">Total Transaksi</p>
                        <h3 class="font-weight-bolder mb-0">
                            {{ $totalTransactions }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <p class="text-sm mb-1 text-uppercase font-weight-bold text-success">Sukses</p>
                        <h3 class="font-weight-bolder mb-0 text-success">
                            {{ $successTransactions }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <p class="text-sm mb-1 text-uppercase font-weight-bold text-warning">Pending</p>
                        <h3 class="font-weight-bolder mb-0 text-warning">
                            {{ $pendingTransactions }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <p class="text-sm mb-1 text-uppercase font-weight-bold text-info">Total Amount</p>
                        <h3 class="font-weight-bolder mb-0">
                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h6>Transaksi Midtrans</h6>
                            <a href="{{ route('admin.midtrans.dashboard') }}" class="btn btn-primary btn-sm">
                                Buka Dashboard Midtrans
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode Pembayaran</th>
                                        <th>Kode Rental</th>
                                        <th>Customer</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr>
                                        <td>{{ $payment['payment_code'] }}</td>
                                        <td>{{ $payment['rental_code'] }}</td>
                                        <td>{{ $payment['customer_name'] }}</td>
                                        <td>Rp {{ number_format($payment['amount'], 0, ',', '.') }}</td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $payment['status'] === 'success' ? 'success' : 
                                                ($payment['status'] === 'pending' ? 'warning' : 'danger') 
                                            }}">
                                                {{ $payment['status'] }}
                                            </span>
                                        </td>
                                        <td>{{ $payment['created_at']->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection