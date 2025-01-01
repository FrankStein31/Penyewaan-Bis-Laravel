@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'User Profile'])
    <div class="card shadow-lg mx-4 card-profile">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('img/users/' . auth()->user()->avatar) }}" class="w-100 border-radius-lg shadow-sm">
                        @else
                            <img src="{{ asset('img/default-avatar.png') }}" class="w-100 border-radius-lg shadow-sm">
                        @endif
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->firstname ?? auth()->user()->username }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ ucfirst(auth()->user()->role) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form role="form" method="POST" action={{ route('customer.profile.update') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Simpan</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">Informasi User</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username</label>
                                        <input class="form-control" type="text" name="username" value="{{ old('username', auth()->user()->username) }}">
                                        @error('username') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Email</label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                                        @error('email') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nama Depan</label>
                                        <input class="form-control" type="text" name="firstname"  value="{{ old('firstname', auth()->user()->firstname) }}">
                                        @error('firstname') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Nama Belakang</label>
                                        <input class="form-control" type="text" name="lastname" value="{{ old('lastname', auth()->user()->lastname) }}">
                                        @error('lastname') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="avatar" class="form-control-label">Foto Profile</label>
                                        <input class="form-control" type="file" name="avatar" accept="image/*">
                                        @error('avatar') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Informasi Kontak</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Alamat</label>
                                        <input class="form-control" type="text" name="address"
                                            value="{{ old('address', auth()->user()->address) }}">
                                        @error('address') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Kota</label>
                                        <input class="form-control" type="text" name="city" value="{{ old('city', auth()->user()->city) }}">
                                        @error('city') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Negara</label>
                                        <input class="form-control" type="text" name="country" value="{{ old('country', auth()->user()->country) }}">
                                        @error('country') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Kode Pos</label>
                                        <input class="form-control" type="text" name="postal" value="{{ old('postal', auth()->user()->postal) }}">
                                        @error('postal') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">No. Telepon</label>
                                        <input class="form-control" type="text" name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                                        @error('phone') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Tentang</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Tentang Saya</label>
                                        <textarea class="form-control" name="about" rows="3">{{ old('about', auth()->user()->about) }}</textarea>
                                        @error('about') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Password</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="current_password" class="form-control-label">Password Saat Ini</label>
                                        <input class="form-control" type="password" name="current_password">
                                        @error('current_password') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-control-label">Password Baru</label>
                                        <input class="form-control" type="password" name="password">
                                        @error('password') <p class="text-danger text-xs pt-1">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation" class="form-control-label">Konfirmasi Password Baru</label>
                                        <input class="form-control" type="password" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection