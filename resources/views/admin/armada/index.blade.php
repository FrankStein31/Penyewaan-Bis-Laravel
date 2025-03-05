@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Daftar Jenis Armada'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Daftar Jenis Armada</h6>
                            <a href="{{ route('admin.armada.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                                <i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Armada
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama Armada</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah Bus</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($armadas as $armada)
                                    <tr>
                                        <td class="align-middle text-center">
                                            <p class="text-sm font-weight-bold mb-0">{{ $armada->armada_id }}</p>
                                        </td>
                                        <td>
                                            <div class="d-flex px-3 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $armada->nama_armada }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-sm font-weight-bold mb-0 px-3">{{ $armada->buses->count() }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('admin.armada.edit', $armada) }}" class="btn bg-gradient-info btn-sm">
                                                <i class="fas fa-pencil-alt me-2"></i>Ubah
                                            </a>
                                            <form action="{{ route('admin.armada.destroy', $armada) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn bg-gradient-danger btn-sm"
                                                        onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="far fa-trash-alt me-2"></i>Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection