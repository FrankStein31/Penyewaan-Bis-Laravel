@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Permintaan Rental'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Daftar Permintaan Rental</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode Rental</th>
                                        <th>Customer</th>
                                        <th>Bus</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Total Harga</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($requests as $rental)
                                        <tr>
                                            <td>{{ $rental->rental_code }}</td>
                                            <td>{{ $rental->user->name }}</td>
                                            <td>{{ $rental->bus->plate_number }}</td>
                                            <td>{{ $rental->start_date->format('d/m/Y') }}</td>
                                            <td>Rp {{ number_format($rental->total_price) }}</td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-warning">
                                                    Pending
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.requests.show', $rental) }}" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                Tidak ada permintaan rental baru
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 