@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pesan Bus'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <p class="mb-0">Form Pemesanan Bus</p>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customer.rentals.store') }}" id="bookingForm">
                            @csrf
                            <input type="hidden" name="bus_id" value="{{ $bus->id }}">
                            
                            @if(session('error'))
                                <div class="alert alert-danger text-white">
                                    {{ session('error') }}
                                </div>
                            @endif
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tanggal & Jam Mulai</label>
                                        <input type="datetime-local" name="start_date" 
                                               class="form-control @error('start_date') is-invalid @enderror" 
                                               value="{{ old('start_date') }}" required>
                                        @error('start_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tanggal & Jam Selesai</label>
                                        <input type="datetime-local" name="end_date" 
                                               class="form-control @error('end_date') is-invalid @enderror" 
                                               value="{{ old('end_date') }}" required>
                                        @error('end_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Driver</label>
                                        <select name="driver_id" class="form-control @error('driver_id') is-invalid @enderror" required>
                                            <option value="">Pilih Driver</option>
                                        </select>
                                        @error('driver_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Conductor</label>
                                        <select name="conductor_id" class="form-control @error('conductor_id') is-invalid @enderror" required>
                                            <option value="">Pilih Conductor</option>
                                        </select>
                                        @error('conductor_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Lokasi Penjemputan</label>
                                        <textarea name="pickup_location" class="form-control @error('pickup_location') is-invalid @enderror" 
                                                  rows="2" required>{{ old('pickup_location') }}</textarea>
                                        @error('pickup_location')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Tujuan</label>
                                        <textarea name="destination" class="form-control @error('destination') is-invalid @enderror" 
                                                  rows="2" required>{{ old('destination') }}</textarea>
                                        @error('destination')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-control-label">Catatan Tambahan</label>
                                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                                  rows="3">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('buses.search') }}" class="btn btn-light m-0">Kembali</a>
                                <button type="submit" class="btn bg-gradient-primary m-0 ms-2" id="submitBtn">
                                    Pesan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Detail Bus</h6>
                    </div>
                    <div class="card-body">
                        @if($bus->image)
                            <img src="{{ asset('img/buses/' . $bus->image) }}" 
                                 class="img-fluid rounded mb-3" alt="Bus Image">
                        @else
                            <img src="{{ asset('img/bus-placeholder.png') }}" 
                                 class="img-fluid rounded mb-3" alt="Bus Image">
                        @endif
                        <h5>{{ $bus->plate_number }}</h5>
                        <p class="mb-2">
                            <span class="badge bg-primary">{{ ucfirst($bus->type) }}</span>
                            <span class="badge bg-info">{{ $bus->capacity }} Kursi</span>
                        </p>
                        <p class="text-sm mb-2">{{ $bus->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-sm">Harga per Hari:</span>
                            <span class="font-weight-bold">
                                Rp {{ number_format($bus->price_per_day, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('bookingForm');
            const startDate = document.querySelector('input[name="start_date"]');
            const endDate = document.querySelector('input[name="end_date"]');
            const driverSelect = document.querySelector('select[name="driver_id"]');
            const conductorSelect = document.querySelector('select[name="conductor_id"]');

            function loadAvailableCrew() {
                if (startDate.value && endDate.value) {
                    fetch(`{{ route('customer.rentals.get-available-crew') }}?start_date=${startDate.value}&end_date=${endDate.value}`)
                        .then(response => response.json())
                        .then(data => {
                            // Reset options
                            driverSelect.innerHTML = '<option value="">Pilih Driver</option>';
                            conductorSelect.innerHTML = '<option value="">Pilih Conductor</option>';

                            // Add drivers
                            if (data.drivers && data.drivers.length > 0) {
                                data.drivers.forEach(driver => {
                                    const option = new Option(
                                        `${driver.name} (${driver.phone})`, 
                                        driver.id
                                    );
                                    driverSelect.add(option);
                                });
                            } else {
                                driverSelect.add(new Option('Tidak ada driver yang tersedia', ''));
                            }

                            // Add conductors
                            if (data.conductors && data.conductors.length > 0) {
                                data.conductors.forEach(conductor => {
                                    const option = new Option(
                                        `${conductor.name} (${conductor.phone})`, 
                                        conductor.id
                                    );
                                    conductorSelect.add(option);
                                });
                            } else {
                                conductorSelect.add(new Option('Tidak ada conductor yang tersedia', ''));
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Gagal memuat data crew: ' + error.message);
                        });
                }
            }

            if (startDate.value && endDate.value) {
                loadAvailableCrew();
            }

            startDate.addEventListener('change', loadAvailableCrew);
            endDate.addEventListener('change', loadAvailableCrew);

            // Hapus event listener submit yang lama
            form.onsubmit = null;
        });
    </script>
    @endpush
@endsection 