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
                                @if($bus->image)
                                    <img src="{{ asset('img/buses/' . $bus->image) }}" class="card-img-top" alt="Bus Image">
                                @else
                                    <img src="{{ asset('img/bus-placeholder.png') }}" class="card-img-top" alt="Bus Image">
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $bus->plate_number }}</h5>
                                    <p class="card-text">
                                        <span class="badge bg-primary">{{ ucfirst($bus->type) }}</span>
                                        <span class="badge bg-info">{{ $bus->capacity }} Kursi</span>
                                        <span class="badge bg-secondary">{{ $bus->armada->nama_armada }}</span>
                                    </p>
                                    <p class="card-text">{{ Str::limit($bus->description, 100) }}</p>
                                    <p class="card-text">
                                        <strong>Harga per Hari:</strong> 
                                        Rp {{ number_format($bus->price_per_day, 0, ',', '.') }}
                                    </p>
                                    <a href="{{ route('buses.book', $bus) }}" class="btn btn-primary">
                                        <i class="fas fa-book"></i> Pesan Sekarang
                                    </a>
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