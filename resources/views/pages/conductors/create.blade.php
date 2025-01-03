@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tambah Kernet'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Tambah Kernet</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('conductors.store') }}" enctype="multipart/form-data">
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
                                        <label for="phone" class="form-control-label">Nomor Telepon</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" type="text" 
                                               name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
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
                                            <option value="on_duty" {{ old('status') == 'on_duty' ? 'selected' : '' }}>Sedang Bertugas</option>
                                            <option value="off" {{ old('status') == 'off' ? 'selected' : '' }}>Tidak Aktif</option>
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
                                               id="is_active" checked>
                                        <label class="form-check-label" for="is_active">Status Aktif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('conductors.index') }}" class="btn btn-light m-0">Kembali</a>
                                <button type="submit" class="btn bg-gradient-primary m-0 ms-2">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 