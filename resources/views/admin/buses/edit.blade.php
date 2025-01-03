@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Bus'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Edit Bus</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('buses.update', $bus) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="armada_id" class="form-control-label">Armada</label>
                                        <select name="armada_id" class="form-control @error('armada_id') is-invalid @enderror" required>
                                            <option value="">Pilih Armada</option>
                                            @foreach($armadas as $armada)
                                                <option value="{{ $armada->armada_id }}" {{ old('armada_id', $bus->armada_id) == $armada->armada_id ? 'selected' : '' }}>
                                                    {{ $armada->nama_armada }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('armada_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Nomor Plat</label>
                                        <input type="text" name="plate_number" class="form-control @error('plate_number') is-invalid @enderror" 
                                               value="{{ old('plate_number', $bus->plate_number) }}" required>
                                        @error('plate_number')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tipe Bus</label>
                                        <select name="type" id="busType" class="form-control @error('type') is-invalid @enderror" required>
                                            <option value="long" {{ old('type', $bus->type) == 'long' ? 'selected' : '' }}>Long (63 Kursi)</option>
                                            <option value="short" {{ old('type', $bus->type) == 'short' ? 'selected' : '' }}>Short (33 Kursi)</option>
                                        </select>
                                        @error('type')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Kapasitas (Kursi)</label>
                                        <input type="number" id="capacity" class="form-control" value="{{ $bus->capacity }}" disabled>
                                        <small class="text-muted">Kapasitas akan disesuaikan otomatis berdasarkan tipe bus</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Harga per Hari</label>
                                        <input type="number" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror" 
                                               value="{{ old('price_per_day', $bus->price_per_day) }}" required min="0" step="0.01">
                                        @error('price_per_day')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Deskripsi</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                                  rows="3">{{ old('description', $bus->description) }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Status</label>
                                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                            <option value="tersedia" {{ old('status', $bus->status) == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="disewa" {{ old('status', $bus->status) == 'disewa' ? 'selected' : '' }}>Disewa</option>
                                            <option value="maintenance" {{ old('status', $bus->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Foto Bus</label>
                                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                                        @error('image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                        @if($bus->image)
                                            <div class="mt-2">
                                                <img src="{{ asset('img/buses/' . $bus->image) }}" 
                                                     alt="Current Image" class="avatar avatar-sm me-3">
                                                <small class="text-muted">Foto saat ini</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" 
                                               id="is_active" {{ $bus->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Status Aktif</label>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('buses.index') }}" class="btn btn-light m-0">Kembali</a>
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

@push('js')
<script>
    document.getElementById('busType').addEventListener('change', function() {
        document.getElementById('capacity').value = this.value === 'long' ? '63' : '33';
    });
</script>
@endpush 