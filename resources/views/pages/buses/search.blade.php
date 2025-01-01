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
                                        <label>Tipe Bus</label>
                                        <select name="type" class="form-control" onchange="this.form.submit()">
                                            <option value="">Semua Tipe</option>
                                            <option value="umum" {{ request('type') == 'umum' ? 'selected' : '' }}>Umum</option>
                                            <option value="pariwisata" {{ request('type') == 'pariwisata' ? 'selected' : '' }}>Pariwisata</option>
                                            <option value="antarkota" {{ request('type') == 'antarkota' ? 'selected' : '' }}>Antar Kota</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Kapasitas Minimum</label>
                                        <input type="number" name="capacity" class="form-control" value="{{ request('capacity') }}" 
                                               placeholder="Jumlah Kursi" onchange="this.form.submit()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Harga Minimum</label>
                                        <input type="number" name="price_min" class="form-control" value="{{ request('price_min') }}" 
                                               placeholder="Rp" onchange="this.form.submit()">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Harga Maksimum</label>
                                        <input type="number" name="price_max" class="form-control" value="{{ request('price_max') }}" 
                                               placeholder="Rp" onchange="this.form.submit()">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Kata Kunci</label>
                                        <div class="input-group">
                                            <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" 
                                                   placeholder="Cari berdasarkan nomor plat atau deskripsi...">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
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