@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pembayaran Berhasil'])
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle text-success fa-4x mb-3"></i>
                        <h3>Pembayaran Berhasil!</h3>
                        <p>Terima kasih atas pembayaran Anda.</p>
                        <a href="{{ route('rentals.index') }}" class="btn btn-primary">
                            Kembali ke Daftar Penyewaan Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
