@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Data Customer'])
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
                            <h6 class="mb-0">Data Customer</h6>
                            <a href="{{ route('customers.create') }}" class="btn bg-gradient-primary btn-sm ms-auto">
                                <i class="fas fa-plus"></i> Tambah Customer
                            </a>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Kontak</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Alamat</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    @if($customer->avatar)
                                                        <img src="{{ asset('img/users/' . $customer->avatar) }}" 
                                                             class="avatar avatar-sm me-3" alt="customer">
                                                    @else
                                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                                             class="avatar avatar-sm me-3" alt="customer">
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $customer->firstname }} {{ $customer->lastname }}</h6>
                                                    <p class="text-xs text-secondary mb-0">{{ $customer->username }}</p>
                                                    <p class="text-xs text-secondary mb-0">{{ $customer->email }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $customer->phone ?? '-' }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $customer->address ?? '-' }}</p>
                                            <p class="text-xs text-secondary mb-0">
                                                {{ $customer->city ? $customer->city . ', ' : '' }}
                                                {{ $customer->country ?? '' }}
                                                {{ $customer->postal ? '(' . $customer->postal . ')' : '' }}
                                            </p>
                                        </td>
                                        <td class="align-middle text-center text-sm">
                                            <span class="badge badge-sm {{ $customer->is_active ? 'bg-gradient-success' : 'bg-gradient-secondary' }}">
                                                {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('customers.edit', $customer) }}" 
                                               class="btn btn-sm bg-gradient-info text-white px-3 mb-0">
                                                <i class="fas fa-pencil-alt me-2"></i> Edit
                                            </a>
                                            <form action="{{ route('customers.destroy', $customer) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm bg-gradient-danger text-white px-3 mb-0"
                                                        onclick="return confirm('Yakin ingin menghapus customer ini?')">
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