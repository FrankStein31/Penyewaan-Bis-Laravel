@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Selamat Datang di Dashboard Anda'])
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Card Status Penyewaan -->
            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm hover-shadow-lg transition-all duration-200">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Belum Dibayar</p>
                                    <h3 class="font-weight-bolder mb-0 mt-2">
                                        {{ $rentals->where('payment_status', 'unpaid')->count() }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-gradient-danger shadow-danger">
                                    <i class="fas fa-coins text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm hover-shadow-lg transition-all duration-200">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Belum Lunas</p>
                                    <h3 class="font-weight-bolder mb-0 mt-2">
                                        {{ $rentals->where('payment_status', 'partial')->count() }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-gradient-warning shadow-warning">
                                    <i class="fas fa-credit-card text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm hover-shadow-lg transition-all duration-200">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Menunggu Konfirmasi Pembayaran</p>
                                    <h3 class="font-weight-bolder mb-0 mt-2">
                                        {{ $rentals->filter(function($rental) {
                                            return $rental->payments->where('status', 'pending')->count() > 0;
                                        })->count() }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-gradient-info shadow-info">
                                    <i class="fas fa-check text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-4">
                <div class="card h-100 shadow-sm hover-shadow-lg transition-all duration-200">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold text-muted">Menunggu Konfirmasi Penyewaan</p>
                                    <h3 class="font-weight-bolder mb-0 mt-2">
                                        {{ $rentals->where('rental_status', 'pending')->count() }}
                                    </h3>
                                </div>
                            </div>
                            <div class="col-4 d-flex align-items-center justify-content-center">
                                <div class="icon-box d-flex align-items-center justify-content-center bg-gradient-primary shadow-primary">
                                    <i class="fas fa-clock text-white text-lg opacity-10"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow-lg {
            transition: all 0.3s ease;
        }
        
        .hover-shadow-lg:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .card:hover .icon-box {
            transform: scale(1.1);
        }

        .numbers h3 {
            font-size: 2rem;
            font-weight: 600;
        }

        .col-4 {
            position: relative;
        }

        .icon-box i {
            font-size: 1.2rem;
        }
    </style>
@endsection