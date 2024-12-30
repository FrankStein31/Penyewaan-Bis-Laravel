@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="card shadow-lg mx-4 card-profile-bottom mb-4">
        <div class="card-body p-4">
            <div class="row gx-4 align-items-center">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="/img/team-1.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto">
                    <div class="h-100">
                        <h6 class="mb-1 fw-bold">
                            {{ auth()->user()->firstname ?? 'Nama Depan' }} {{ auth()->user()->lastname ?? 'Nama Belakang' }}
                        </h6>
                        <p class="mb-0 text-muted small">
                            {{ auth()->user()->about ?? 'Status' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="alert">
        @include('components.alert')
    </div>

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow-sm">
                    <form role="form" method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0 p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="mb-1 text-primary">Edit Profil</h6>
                                    <p class="text-sm text-muted mb-0">Perbarui informasi profil Anda</p>
                                </div>
                                <button type="submit" class="btn btn-primary px-3 rounded-pill shadow-sm">
                                    <i class="fas fa-save me-2"></i>
                                    <span>Simpan Perubahan</span>
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="mb-4">
                                <h6 class="text-uppercase text-sm fw-bold mb-3">Informasi Pengguna</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="username" class="form-control-label small text-muted">Username</label>
                                            <input class="form-control" type="text" name="username" value="{{ old('username', auth()->user()->username) }}">
                                            @error('username')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-control-label small text-muted">Email</label>
                                            <input class="form-control" type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="firstname" class="form-control-label small text-muted">Nama Depan</label>
                                            <input class="form-control" type="text" name="firstname" value="{{ old('firstname', auth()->user()->firstname) }}">
                                            @error('firstname')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lastname" class="form-control-label small text-muted">Nama Belakang</label>
                                            <input class="form-control" type="text" name="lastname" value="{{ old('lastname', auth()->user()->lastname) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <div class="mb-4">
                                <h6 class="text-uppercase text-sm fw-bold mb-3">Informasi Kontak</h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="address" class="form-control-label small text-muted">Alamat</label>
                                            <input class="form-control" type="text" name="address" value="{{ old('address', auth()->user()->address) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="city" class="form-control-label small text-muted">Kota</label>
                                            <input class="form-control" type="text" name="city" value="{{ old('city', auth()->user()->city) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="country" class="form-control-label small text-muted">Negara</label>
                                            <input class="form-control" type="text" name="country" value="{{ old('country', auth()->user()->country) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="postal" class="form-control-label small text-muted">Kode Pos</label>
                                            <input class="form-control" type="text" name="postal" value="{{ old('postal', auth()->user()->postal) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <div>
                                <h6 class="text-uppercase text-sm fw-bold mb-3">Tentang Saya</h6>
                                <div class="form-group">
                                    <label for="about" class="form-control-label small text-muted">Tentang Saya</label>
                                    <textarea class="form-control" name="about" rows="3">{{ old('about', auth()->user()->about) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="text-center">
                        <div class="small mb-2">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            made with <i class="fa fa-heart text-danger mx-1"></i> by
                            <a href="https://www.instagram.com/steinliejoki?igsh=ODRkeGpuN3U3MGhp" 
                               class="font-weight-bold text-primary" 
                               target="_blank">
                                Owner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endsection