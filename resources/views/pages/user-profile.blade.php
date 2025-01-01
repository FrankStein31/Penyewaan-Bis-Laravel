@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Profile'])
    <div class="container-fluid py-4">
        <div class="card shadow-lg mx-4 card-profile mb-4">
            <div class="card-body p-4">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="avatar avatar-xl position-relative">
                            <img src="{{ auth()->user()->avatar ? asset('img/users/' . auth()->user()->avatar) : '/img/team-1.jpg' }}" 
                                alt="profile_image" 
                                class="w-100 border-radius-lg shadow-sm">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ auth()->user()->firstname ?? 'Nama Depan' }} {{ auth()->user()->lastname ?? 'Nama Belakang' }}
                            </h5>
                            <p class="mb-0 text-muted font-weight-normal text-sm">
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

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <form role="form" method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h5 class="mb-0 text-primary">Edit Profil</h5>
                                    <p class="text-sm text-muted mb-0">Perbarui informasi profil Anda</p>
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill">
                                    <i class="fas fa-save me-2"></i>Simpan
                                </button>
                            </div>
                        </div>
                        
                        <div class="card-body p-4">
                            <!-- Profile Photo Section -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-sm fw-bold mb-3">
                                    <i class="fas fa-camera me-2"></i>Foto Profil
                                </h6>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="d-flex align-items-center mb-3">
                                                @if(auth()->user()->avatar)
                                                    <img src="{{ asset('img/users/' . auth()->user()->avatar) }}" 
                                                         alt="Current Avatar" 
                                                         class="avatar avatar-lg me-3">
                                                @endif
                                                <div class="flex-grow-1">
                                                    <label class="form-control-label small text-muted d-block mb-2">Upload Foto Baru</label>
                                                    <input class="form-control form-control-sm" type="file" name="avatar" accept="image/*">
                                                </div>
                                            </div>
                                            @error('avatar')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <!-- User Information Section -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-sm fw-bold mb-3">
                                    <i class="fas fa-user me-2"></i>Informasi Pengguna
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Username</label>
                                            <input class="form-control form-control-sm" type="text" name="username" 
                                                   value="{{ old('username', auth()->user()->username) }}">
                                            @error('username')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Email</label>
                                            <input class="form-control form-control-sm" type="email" name="email" 
                                                   value="{{ old('email', auth()->user()->email) }}">
                                            @error('email')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Nama Depan</label>
                                            <input class="form-control form-control-sm" type="text" name="firstname" 
                                                   value="{{ old('firstname', auth()->user()->firstname) }}">
                                            @error('firstname')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Nama Belakang</label>
                                            <input class="form-control form-control-sm" type="text" name="lastname" 
                                                   value="{{ old('lastname', auth()->user()->lastname) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <!-- Contact Information Section -->
                            <div class="mb-4">
                                <h6 class="text-uppercase text-sm fw-bold mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Informasi Kontak
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Alamat Lengkap</label>
                                            <input class="form-control form-control-sm" type="text" name="address" 
                                                   value="{{ old('address', auth()->user()->address) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Kota</label>
                                            <input class="form-control form-control-sm" type="text" name="city" 
                                                   value="{{ old('city', auth()->user()->city) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Negara</label>
                                            <input class="form-control form-control-sm" type="text" name="country" 
                                                   value="{{ old('country', auth()->user()->country) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label text-muted small">Kode Pos</label>
                                            <input class="form-control form-control-sm" type="text" name="postal" 
                                                   value="{{ old('postal', auth()->user()->postal) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="horizontal dark">

                            <!-- About Section -->
                            <div>
                                <h6 class="text-uppercase text-sm fw-bold mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Tentang Saya
                                </h6>
                                <div class="form-group">
                                    <label class="form-label text-muted small">Deskripsi Singkat</label>
                                    <textarea class="form-control form-control-sm" name="about" rows="4" 
                                              placeholder="Tulis sedikit tentang diri Anda...">{{ old('about', auth()->user()->about) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footers.auth.footer')
@endsection