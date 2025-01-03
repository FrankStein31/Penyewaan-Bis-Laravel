@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Data Bus'])
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
                            <h6 class="mb-0">Data Bus</h6>
                            <a href="{{ route('buses.create') }}" class="btn bg-gradient-primary btn-sm ms-auto">
                                <i class="fas fa-plus"></i> Tambah Bus
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bus</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Armada</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kapasitas</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Harga/Hari</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status Aktif</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($buses as $bus)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @if($bus->image)
                                                        <img src="{{ asset('img/buses/' . $bus->image) }}" 
                                                             class="avatar avatar-sm me-3" alt="bus">
                                                    @else
                                                        <img src="{{ asset('img/bus-placeholder.png') }}" 
                                                             class="avatar avatar-sm me-3" alt="bus">
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $bus->plate_number }}</h6>
                                                    <p class="text-xs text-secondary mb-0">
                                                        {{ Str::limit($bus->description, 30) }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $bus->armada->nama_armada ?? 'Tidak Ada' }}</p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm {{ 
                                                $bus->type == 'long' ? 'bg-gradient-info' : 'bg-gradient-success'
                                            }}">
                                                {{ $bus->type == 'long' ? 'Long (63)' : 'Short (33)' }}
                                            </span>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $bus->capacity }} Kursi</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">Rp {{ number_format($bus->price_per_day, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm {{ 
                                                $bus->status == 'tersedia' ? 'bg-gradient-success' : 
                                                ($bus->status == 'disewa' ? 'bg-gradient-warning' : 'bg-gradient-danger') 
                                            }}">
                                                {{ ucfirst($bus->status) }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-sm {{ $bus->is_active ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                {{ $bus->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('buses.edit', $bus) }}" 
                                               class="btn btn-sm bg-gradient-info text-white px-3 mb-0">
                                                <i class="fas fa-pencil-alt me-2"></i> Edit
                                            </a>
                                            <form action="{{ route('buses.destroy', $bus) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-gradient-danger text-white px-3 mb-0"
                                                        onclick="return confirm('Yakin ingin menghapus bus ini?')">
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