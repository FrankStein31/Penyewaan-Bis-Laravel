@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Pesanan'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">Detail Pesanan</h6>
                            <div>
                                @if($rental->rental_status === 'pending')
                                    <form action="{{ route('customer.rentals.cancel', $rental) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                            <i class="fas fa-times"></i> Batalkan Pesanan
                                        </button>
                                    </form>
                                @elseif($rental->rental_status === 'confirmed' && $rental->payment_status !== 'paid')
                                    <a href="{{ route('customer.payments.form', $rental) }}" 
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Kode Pesanan:</p>
                                <h6>{{ $rental->rental_code }}</h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-sm mb-0">Status Pesanan:</p>
                                <span class="badge bg-{{ 
                                    $rental->rental_status === 'pending' ? 'warning' : 
                                    ($rental->rental_status === 'confirmed' ? 'info' :
                                    ($rental->rental_status === 'ongoing' ? 'primary' :
                                    ($rental->rental_status === 'completed' ? 'success' : 'danger'))) 
                                }}">
                                    {{ $rental->rental_status === 'pending' ? 'Menunggu' :
                                       ($rental->rental_status === 'confirmed' ? 'Dikonfirmasi' :
                                       ($rental->rental_status === 'ongoing' ? 'Sedang Berlangsung' :
                                       ($rental->rental_status === 'completed' ? 'Selesai' : 'Dibatalkan'))) }}
                                </span>
                                
                                <p class="text-sm mb-0 mt-2">Status Pembayaran:</p>
                                <span class="badge bg-{{ 
                                    $rental->payment_status === 'unpaid' ? 'danger' : 
                                    ($rental->payment_status === 'partial' ? 'warning' : 'success') 
                                }}">
                                    {{ $rental->payment_status === 'unpaid' ? 'Belum Dibayar' : 
                                       ($rental->payment_status === 'partial' ? 'Dibayar Sebagian' : 
                                       ($rental->payment_status === 'paid' ? 'Lunas' : 'Dibatalkan')) }}
                                </span>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Tanggal Mulai:</p>
                                <h6>{{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y H:i') }}</h6>
                            </div>
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Tanggal Selesai:</p>
                                <h6>{{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y H:i') }}</h6>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-sm mb-0">Lokasi Penjemputan:</p>
                                <h6>{{ $rental->pickup_location }}</h6>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-sm mb-0">Tujuan:</p>
                                <h6>{{ $rental->destination }}</h6>
                            </div>
                        </div>
                        @if($rental->notes)
                            <hr class="horizontal dark">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-sm mb-0">Catatan:</p>
                                    <h6>{{ $rental->notes }}</h6>
                                </div>
                            </div>
                        @endif
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Total Hari:</p>
                                <h6>{{ $rental->total_days }} hari</h6>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="text-sm mb-0">Total Harga:</p>
                                <h6>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</h6>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="text-sm mb-0">Jenis Paket:</p>
                                <h6>{{ $rental->rental_package === 'day' ? 'Paket Day (1 Hari)' : 'Paket Trip' }}</h6>
                            </div>
                        </div>
                        @if($rental->payments->count() > 0)
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text-sm mb-2">Riwayat Pembayaran:</p>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Jumlah</th>
                                                <th>Metode</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rental->payments as $payment)
                                            <tr>
                                                <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                                <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($payment->payment_method === 'cash')
                                                        Tunai
                                                    @elseif($payment->payment_method === 'transfer') 
                                                        Transfer Bank
                                                    @else
                                                        {{ $payment->payment_method }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $payment->status === 'verified' ? 'success' : 'warning' }}">
                                                        {{ $payment->status === 'verified' ? 'Terverifikasi' : 'Menunggu' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($rental->payment_status !== 'paid')
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-white mb-0">Sisa Pembayaran:</h6>
                                            <h4 class="text-white mb-0">
                                                Rp {{ number_format($rental->total_price - $rental->payments->sum('amount'), 0, ',', '.') }}
                                            </h4>
                                        </div>
                                        @if($rental->rental_status === 'confirmed')
                                        <a href="{{ route('customer.payments.form', $rental) }}" 
                                           class="btn btn-white btn-sm">
                                            Bayar Sekarang
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mb-0">Detail Bus</h6>
                        <hr class="horizontal dark">
                        @if($rental->bus->photo)
                            <img src="{{ asset('storage/' . $rental->bus->photo) }}" alt="Bus Photo" class="img-fluid mb-3 rounded">
                        @endif
                        <h5>{{ $rental->bus->plate_number }}</h5>
                        <p class="mb-2">
                            <span class="badge bg-primary">{{ ucfirst($rental->bus->type) }}</span>
                            <span class="badge bg-info">{{ $rental->bus->capacity }} Kursi</span>
                            <span class="badge bg-secondary">{{ $rental->bus->armada->nama_armada }}</span>
                        </p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-sm">Armada:</span>
                            <span class="font-weight-bold">{{ $rental->bus->armada->nama_armada }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-sm">Harga per Hari:</span>
                            <span class="font-weight-bold">
                                Rp {{ number_format($rental->bus->price_per_day, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="text-uppercase text-sm">Informasi Crew</h6>
                        <div class="form-group">
                            <label class="form-control-label">Driver</label>
                            <p class="form-control-static">
                                @if($rental->driver)
                                    <div class="d-flex align-items-center">
                                        @if($rental->driver->photo)
                                            <img src="{{ Storage::url('drivers/'.$rental->driver->photo) }}" 
                                                 class="avatar avatar-sm rounded-circle me-2" 
                                                 alt="Driver Photo">
                                        @endif
                                        <div>
                                            {{ $rental->driver->name }}
                                            <small class="d-block text-secondary">
                                                No. HP: {{ $rental->driver->phone }}
                                            </small>
                                            <!-- <small class="d-block text-secondary">
                                                SIM: {{ $rental->driver->license_number }}
                                                (Exp: {{ date('d/m/Y', strtotime($rental->driver->license_expire)) }})
                                            </small> -->
                                        </div>
                                    </div>
                                @else
                                    <span class="text-secondary">Tidak ada</span>
                                @endif
                            </p>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Kondektur</label>
                            <p class="form-control-static">
                                @if($rental->conductor)
                                    <div class="d-flex align-items-center">
                                        @if($rental->conductor->photo)
                                            <img src="{{ asset('storage/conductors/' . $rental->conductor->photo) }}" 
                                                 class="avatar avatar-sm rounded-circle me-2" 
                                                 alt="Conductor Photo">
                                        @endif
                                        <div>
                                            {{ $rental->conductor->name }}
                                            <small class="d-block text-secondary">
                                                No. HP: {{ $rental->conductor->phone }}
                                            </small>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-secondary">Tidak ada</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection 