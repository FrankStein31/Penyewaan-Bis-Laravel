@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Dashboard Midtrans'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex justify-content-between">
                            <h6>Dashboard Midtrans</h6>
                            <a href="{{ route('admin.midtrans.index') }}" class="btn btn-secondary btn-sm">
                                Kembali ke Transaksi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Untuk mengakses Dashboard Midtrans, silakan klik tombol di bawah ini
                        </div>
                        <div class="text-center">
                            <a href="https://dashboard.sandbox.midtrans.com" 
                               target="_blank" 
                               rel="noopener noreferrer" 
                               class="btn btn-primary">
                                Buka Dashboard Midtrans di Tab Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection