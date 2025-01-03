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
                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-control-label">Pilih Paket</label>
                                        <select name="rental_package" id="rentalPackage" class="form-control" required>
                                            <option value="day">Paket Day (1 Hari)</option>
                                            <option value="trip">Paket Tolak (Lebih dari 1 hari)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Tanggal & Jam Mulai</label>
                                        <input type="datetime-local" name="start_date" id="startDate"
                                               class="form-control @error('start_date') is-invalid @enderror" 
                                               value="{{ old('start_date') }}" required>
                                        @error('start_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6" id="endDateContainer" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-control-label">Tanggal & Jam Selesai</label>
                                        <input type="datetime-local" name="end_date" id="endDate"
                                               class="form-control @error('end_date') is-invalid @enderror" 
                                               value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Supir</label>
                                        <select name="driver_id" class="form-control @error('driver_id') is-invalid @enderror" required>
                                            <option value="">Pilih Supir</option>
                                        </select>
                                        @error('driver_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Kernet</label>
                                        <select name="conductor_id" class="form-control @error('conductor_id') is-invalid @enderror" required>
                                            <option value="">Pilih Kernet</option>
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
            const packageSelect = document.getElementById('rentalPackage');
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const endDateContainer = document.getElementById('endDateContainer');
            const driverSelect = document.querySelector('select[name="driver_id"]');
            const conductorSelect = document.querySelector('select[name="conductor_id"]');

            // Handle package selection
            packageSelect.addEventListener('change', function() {
                if (this.value === 'day') {
                    endDateContainer.style.display = 'none';
                    endDate.removeAttribute('required');
                    // Set end_date 24 jam setelah start_date
                    if (startDate.value) {
                        const start = new Date(startDate.value);
                        const end = new Date(start);
                        end.setHours(end.getHours() + 24);
                        endDate.value = end.toISOString().slice(0, 16);
                    }
                } else {
                    endDateContainer.style.display = 'block';
                    endDate.setAttribute('required', 'required');
                }
            });

            // Handle start date change
            startDate.addEventListener('change', function() {
                if (packageSelect.value === 'day') {
                    const start = new Date(this.value);
                    const end = new Date(start);
                    end.setHours(end.getHours() + 24);
                    endDate.value = end.toISOString().slice(0, 16);
                }
                loadAvailableCrew();
            });

            // Handle end date change
            endDate.addEventListener('change', loadAvailableCrew);

            function loadAvailableCrew() {
                const start = startDate.value;
                const end = packageSelect.value === 'day' ? 
                    new Date(new Date(start).getTime() + (24 * 60 * 60 * 1000)).toISOString().slice(0, 16) : 
                    endDate.value;

                if (start && end) {
                    fetch(`{{ route('customer.rentals.get-available-crew') }}?start_date=${start}&end_date=${end}`)
                        .then(response => response.json())
                        .then(data => {
                            // Reset options
                            driverSelect.innerHTML = '<option value="">Pilih Supir</option>';
                            conductorSelect.innerHTML = '<option value="">Pilih Kernet</option>';

                            // Add drivers
                            if (data.drivers && data.drivers.length > 0) {
                                data.drivers.forEach(driver => {
                                    driverSelect.add(new Option(
                                        `${driver.name} (${driver.phone})`, 
                                        driver.id
                                    ));
                                });
                            } else {
                                driverSelect.add(new Option('Tidak ada driver yang tersedia', ''));
                            }

                            // Add conductors
                            if (data.conductors && data.conductors.length > 0) {
                                data.conductors.forEach(conductor => {
                                    conductorSelect.add(new Option(
                                        `${conductor.name} (${conductor.phone})`, 
                                        conductor.id
                                    ));
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

            // Initial setup
            if (packageSelect.value === 'day') {
                endDateContainer.style.display = 'none';
                endDate.removeAttribute('required');
            }

            if (startDate.value && endDate.value) {
                loadAvailableCrew();
            }
        });
    </script>
    @endpush
@endsection 