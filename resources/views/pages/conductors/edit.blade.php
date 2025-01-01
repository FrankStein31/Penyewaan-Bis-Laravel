@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Kernet'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Edit Kernet</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('conductors.update', $conductor) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label">Nama Lengkap</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text" 
                                               name="name" value="{{ old('name', $conductor->name) }}" required>
                                        @error('name')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone" class="form-control-label">Nomor Telepon</label>
                                        <input class="form-control @error('phone') is-invalid @enderror" type="text" 
                                               name="phone" value="{{ old('phone', $conductor->phone) }}" required>
                                        @error('phone')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address" class="form-control-label">Alamat</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  name="address" rows="3" required>{{ old('address', $conductor->address) }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status" class="form-control-label">Status</label>
                                        <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                                            <option value="available" {{ old('status', $conductor->status) == 'available' ? 'selected' : '' }}>Available</option>
                                            <option value="on_duty" {{ old('status', $conductor->status) == 'on_duty' ? 'selected' : '' }}>On Duty</option>
                                            <option value="off" {{ old('status', $conductor->status) == 'off' ? 'selected' : '' }}>Off</option>
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
                                        @if($conductor->photo)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/conductors/' . $conductor->photo) }}" 
                                                     alt="Current Photo" class="avatar avatar-sm me-3">
                                                <small class="text-muted">Foto saat ini</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" 
                                               id="is_active" {{ $conductor->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Status Aktif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('conductors.index') }}" class="btn btn-light m-0"><i class="fas fa-arrow-left me-2"></i><span>Kembali</span></a>
                                
                                <button type="submit" class="btn bg-gradient-primary m-0 ms-2"><i class="fas fa-save me-2"></i><span>Simpan Perubahan</span></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 