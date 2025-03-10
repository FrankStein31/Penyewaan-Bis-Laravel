@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Ranking Armada</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>Ranking</th>
                                    <th>No. Polisi</th>
                                    <th>Tipe</th>
                                    <th>Total Penyewaan</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topBuses as $index => $bus)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $bus->plate_number }}</td>
                                    <td>{{ ucfirst($bus->type) }}</td>
                                    <td>{{ $bus->rentals_count }}</td>
                                    <td>{{ number_format($bus->ratings_avg_rating ?? 0, 1) }}</td>
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