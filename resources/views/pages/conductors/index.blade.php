@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Data Kernet'])
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
                            <h6 class="mb-0">Data Kernet</h6>
                            <a href="{{ route('conductors.create') }}" class="btn bg-gradient-primary btn-sm ms-auto">
                                <i class="fas fa-plus"></i> Tambah Kernet
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kernet</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">NIK</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kontak</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Aktif</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($conductors as $conductor)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @if($conductor->photo)
                                                        <img src="{{ asset('storage/conductors/' . $conductor->photo) }}" class="avatar avatar-sm me-3" alt="user1">
                                                    @else
                                                        <img src="{{ asset('img/default-avatar.png') }}" class="avatar avatar-sm me-3" alt="user1">
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $conductor->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $conductor->nik }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $conductor->phone }}</p>
                                            <p class="text-xs text-secondary mb-0">{{ $conductor->address }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            @if($conductor->status == 'available')
                                                <span class="badge badge-sm bg-gradient-success">Tersedia</span>
                                            @elseif($conductor->status == 'on_duty')
                                                <span class="badge badge-sm bg-gradient-warning">Bertugas</span>
                                            @else
                                                <span class="badge badge-sm bg-gradient-secondary">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm {{ $conductor->is_active ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                {{ $conductor->is_active ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('conductors.edit', $conductor) }}" 
                                               class="btn btn-sm bg-gradient-info text-white px-3 mb-0">
                                                <i class="fas fa-pencil-alt me-2"></i> Edit
                                            </a>
                                            <form action="{{ route('conductors.destroy', $conductor) }}" 
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