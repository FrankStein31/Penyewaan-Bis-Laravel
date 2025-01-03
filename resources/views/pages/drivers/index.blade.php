@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Data Supir'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                        <span class="alert-icon"><i class="fas fa-check"></i></span>
                        <span class="alert-text">{{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                        <span class="alert-icon"><i class="fas fa-times"></i></span>
                        <span class="alert-text">{{ session('error') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Data Supir</h6>
                            <a href="{{ route('drivers.create') }}" class="btn bg-gradient-primary btn-sm ms-auto">
                                <i class="fas fa-plus"></i> Tambah Supir
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Foto</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No. SIM</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Telepon</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Alamat</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Masa Berlaku SIM</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Aktif</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($drivers as $driver)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                @if($driver->photo)
                                                    <img src="{{ Storage::url('drivers/'.$driver->photo) }}" 
                                                         class="avatar avatar-sm me-3" alt="user1">
                                                @else
                                                    <img src="{{ asset('img/default-avatar.png') }}" 
                                                         class="avatar avatar-sm me-3" alt="user1">
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $driver->name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $driver->license_number }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $driver->phone }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $driver->address }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $driver->license_expire->format('d/m/Y') }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $driver->status == 'available' ? 'success' : 
                                                ($driver->status == 'on_duty' ? 'warning' : 'secondary') 
                                            }}">{{ 
                                                $driver->status == 'available' ? 'Tersedia' :
                                                ($driver->status == 'on_duty' ? 'Bertugas' : 'Tidak Tersedia')
                                            }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ $driver->is_active ? 'success' : 'danger' }}">
                                                {{ $driver->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('drivers.edit', $driver) }}" 
                                               class="btn btn-sm bg-gradient-info text-white px-3 mb-0">
                                                <i class="fas fa-pencil-alt me-2"></i> Edit
                                            </a>
                                            <form action="{{ route('drivers.destroy', $driver) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-gradient-danger text-white px-3 mb-0"
                                                        onclick="return confirm('Yakin ingin menghapus?')">
                                                    <i class="fas fa-trash me-2"></i> Hapus
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