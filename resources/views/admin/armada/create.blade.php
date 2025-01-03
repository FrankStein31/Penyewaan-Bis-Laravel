@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tambah Armada'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Tambah Armada Baru</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.armada.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="form-control-label" for="nama_armada">Nama Armada</label>
                                <input type="text" class="form-control @error('nama_armada') is-invalid @enderror" 
                                       id="nama_armada" name="nama_armada" 
                                       placeholder="Masukkan nama armada"
                                       value="{{ old('nama_armada') }}"
                                       required>
                                @error('nama_armada')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('admin.armada.index') }}" class="btn btn-secondary me-2">Kembali</a>
                                <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection