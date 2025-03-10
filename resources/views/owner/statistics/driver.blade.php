@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Jam Terbang Supir</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Supir</th>
                                    <th>Total Penyewaan</th>
                                    <!-- <th>Rating</th> -->
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drivers as $driver)
                                <tr>
                                    <td>{{ $driver->name }}</td>
                                    <td>{{ $driver->rentals_count }}</td>
                                    <!-- <td>{{ number_format($driver->ratings_avg_rating ?? 0, 1) }}</td> -->
                                    <td>
                                        <span class="badge badge-sm bg-{{ $driver->status == 'available' ? 'success' : 'warning' }}">
                                            {{ ucfirst($driver->status) }}
                                        </span>
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
</div>
@endsection