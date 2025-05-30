@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Cari Bus'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Filter Pencarian</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('buses.search') }}" method="GET" id="searchForm">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Armada</label>
                                        <select name="armada_id" class="form-control" onchange="this.form.submit()">
                                            <option value="">Semua Armada</option>
                                            @foreach($armadas as $armada)
                                                <option value="{{ $armada->armada_id }}" {{ request('armada_id') == $armada->armada_id ? 'selected' : '' }}>
                                                    {{ $armada->nama_armada }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tipe Bus Pariwisata</label>
                                        <select name="type" id="busType" class="form-control" onchange="updateMinValues(this.value)">
                                            <option value="">Semua Tipe</option>
                                            <option value="long" {{ request('type') == 'long' ? 'selected' : '' }}>Long (63 Kursi)</option>
                                            <option value="short" {{ request('type') == 'short' ? 'selected' : '' }}>Short (33 Kursi)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Kapasitas Minimum</label>
                                        <input type="number" name="capacity" id="minCapacity" class="form-control" 
                                               value="{{ request('capacity') }}" placeholder="Jumlah Kursi">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Harga Minimum</label>
                                        <input type="number" name="price_min" id="minPrice" class="form-control" 
                                               value="{{ request('price_min') }}" placeholder="Rp">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Harga Maksimum</label>
                                        <input type="number" name="price_max" class="form-control" value="{{ request('price_max') }}" 
                                               placeholder="Rp" onchange="this.form.submit()">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Kata Kunci</label>
                                        <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" 
                                               placeholder="Cari berdasarkan nomor plat atau deskripsi...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button class="btn btn-primary w-100" type="submit">
                                            <i class="fas fa-search"></i> Terapkan Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    @forelse($buses as $bus)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                    @if($bus->image)
                                        <img src="{{ asset('img/buses/' . $bus->image) }}" 
                                             class="img-fluid border-radius-lg" alt="Bus Image">
                                    @else
                                        <img src="{{ asset('img/bus-placeholder.png') }}" 
                                             class="img-fluid border-radius-lg" alt="Bus Image">
                                    @endif
                                </div>

                                <div class="card-body pt-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h5 class="mb-0">{{ $bus->plate_number }}</h5>
                                        <span class="badge badge-sm {{ 
                                            $bus->status == 'tersedia' ? 'bg-gradient-success' : 
                                            ($bus->status == 'disewa' ? 'bg-gradient-warning' : 'bg-gradient-danger') 
                                        }}">
                                            {{ ucfirst($bus->status) }}
                                        </span>
                                    </div>
                                    <p class="mb-2">
                                        <span class="badge bg-primary">{{ ucfirst($bus->type) }}</span>
                                        <span class="badge bg-info">{{ $bus->capacity }} Kursi</span>
                                        <span class="badge bg-secondary">{{ $bus->armada->nama_armada }}</span>
                                    </p>
                                    <p class="text-sm mb-2">{{ Str::limit($bus->description, 100) }}</p>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-sm">Harga per Hari:</span>
                                        <span class="font-weight-bold">
                                            Rp {{ number_format($bus->price_per_day, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    @if($bus->rentals->isNotEmpty())
                                    <div class="alert alert-warning py-2 mb-2">
                                        <p class="text-sm mb-0"><strong>Jadwal Terpesan:</strong></p>
                                        <ul class="mb-0 ps-3">
                                            @foreach($bus->rentals->sortBy('start_date') as $rental)
                                            <li class="text-sm">
                                                {{ $rental->start_date->format('d M Y H:i') }} - 
                                                {{ $rental->end_date->format('d M Y H:i') }}
                                                <span class="badge badge-sm {{ 
                                                    $rental->rental_status == 'confirmed' ? 'bg-gradient-info' : 
                                                    ($rental->rental_status == 'ongoing' ? 'bg-gradient-primary' : 'bg-gradient-secondary') 
                                                }}">
                                                    {{ 
                                                        $rental->rental_status == 'confirmed' ? 'Dikonfirmasi' : 
                                                        ($rental->rental_status == 'ongoing' ? 'Sedang Berlangsung' : ucfirst($rental->rental_status))
                                                    }}
                                                </span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="text-center">
                                        <a href="{{ route('buses.book', $bus) }}" 
                                           class="btn bg-gradient-primary w-100">
                                            <i class="fas fa-calendar-plus me-2"></i>Pesan Sekarang
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info text-white">
                                Tidak ada bus yang tersedia dengan kriteria pencarian Anda.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 

@push('js')
<script>
const busData = {!! json_encode([
    'long' => [
        'capacity' => 63,
        'min_price' => $buses->where('type', 'long')->min('price_per_day') ?? 0
    ],
    'short' => [
        'capacity' => 33,
        'min_price' => $buses->where('type', 'short')->min('price_per_day') ?? 0
    ]
]) !!};

function updateMinValues(type) {
    if (type) {
        const data = busData[type];
        document.getElementById('minCapacity').value = data.capacity;
        document.getElementById('minPrice').value = data.min_price;
    } else {
        document.getElementById('minCapacity').value = '';
        document.getElementById('minPrice').value = '';
    }
}

// Set nilai awal jika tipe sudah dipilih tanpa melakukan submit
document.addEventListener('DOMContentLoaded', function() {
    const selectedType = document.getElementById('busType').value;
    if (selectedType) {
        updateMinValues(selectedType);
    }
});
</script>
@endpush 