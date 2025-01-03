@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tambah Supir'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Berhasil!</h6>
                                <p class="mb-0">{{ session('success') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Terjadi Kesalahan!</h6>
                                <p class="mb-0">{{ session('error') }}</p>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Tambah Supir</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('drivers.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Nama Lengkap</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" 
                                               name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="license_number" class="form-control-label">Nomor SIM</label>
                                        <input class="form-control @error('license_number') is-invalid @enderror" type="text" 
                                               name="license_number" value="{{ old('license_number') }}" required>
                                        @error('license_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-control-label">Nomor Telepon</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" type="text" 
                                               name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="license_expire" class="form-control-label">Masa Berlaku SIM</label>
                                        <input class="form-control @error('license_expire') is-invalid @enderror" type="date" 
                                               name="license_expire" value="{{ old('license_expire') }}" required>
                                        @error('license_expire')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address" class="form-control-label">Alamat</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  name="address" rows="3" required>{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-control-label">Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="on_duty" {{ old('status') == 'on_duty' ? 'selected' : '' }}>Bertugas</option>
                                            <option value="off" {{ old('status') == 'off' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="photo" class="form-control-label">Foto</label>
                                        <input class="form-control @error('photo') is-invalid @enderror" type="file" 
                                               name="photo" accept="image/*">
                                        @error('photo')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" 
                                               id="is_active" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Status Aktif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('drivers.index') }}" class="btn btn-light px-3 rounded-pill shadow-sm">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    <span>Kembali</span>
                                </a>
                                <button type="submit" class="btn btn-primary px-3 rounded-pill shadow-sm ms-2">
                                    <i class="fas fa-save me-2"></i>
                                    <span>Simpan</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection