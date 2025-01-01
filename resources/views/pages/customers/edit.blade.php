@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Customer'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Edit Customer</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customers.update', $customer) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Username</label>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                               value="{{ old('username', $customer->username) }}" required>
                                        @error('username')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Email</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $customer->email) }}" required>
                                        @error('email')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama Depan</label>
                                        <input type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror" 
                                               value="{{ old('firstname', $customer->firstname) }}" required>
                                        @error('firstname')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nama Belakang</label>
                                        <input type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror" 
                                               value="{{ old('lastname', $customer->lastname) }}">
                                        @error('lastname')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">No. Telepon</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                               value="{{ old('phone', $customer->phone) }}">
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Alamat</label>
                                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" 
                                               value="{{ old('address', $customer->address) }}">
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Kota</label>
                                        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" 
                                               value="{{ old('city', $customer->city) }}">
                                        @error('city')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Negara</label>
                                        <input type="text" name="country" class="form-control @error('country') is-invalid @enderror" 
                                               value="{{ old('country', $customer->country) }}">
                                        @error('country')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-control-label">Kode Pos</label>
                                        <input type="text" name="postal" class="form-control @error('postal') is-invalid @enderror" 
                                               value="{{ old('postal', $customer->postal) }}">
                                        @error('postal')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Tentang</label>
                                        <textarea name="about" class="form-control @error('about') is-invalid @enderror">{{ old('about', $customer->about) }}</textarea>
                                        @error('about')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Foto</label>
                                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                        @error('avatar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        @if($customer->avatar)
                                            <div class="mt-2">
                                                <img src="{{ asset('img/users/' . $customer->avatar) }}" 
                                                     alt="Current Avatar" class="avatar avatar-sm me-3">
                                                <small class="text-muted">Foto saat ini</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" 
                                               id="is_active" {{ $customer->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Status Aktif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('customers.index') }}" class="btn btn-light m-0">Kembali</a>
                                <button type="submit" class="btn bg-gradient-primary m-0 ms-2">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 