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
                                    <button class="btn btn-success btn-sm" onclick="payNow({{ $rental->id }})">
                                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                                    </button>
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
                                
                                <p class="text-sm mb-0 mt-2">Status Pembayaran :</p>
                                <span class="badge badge-sm bg-gradient-{{ 
                                    $rental->payments->isNotEmpty() && $rental->payments->first()->status == 'pending' ? 'warning' : 
                                    ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'success' ? 'success' : 
                                    ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'failed' ? 'danger' : 'secondary'))
                                }}">
                                    {{ 
                                        $rental->payments->isNotEmpty() && $rental->payments->first()->status == 'pending' ? 'Menunggu Konfirmasi' :
                                        ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'success' ? 'Pembayaran Berhasil' :
                                        ($rental->payments->isNotEmpty() && $rental->payments->first()->status == 'failed' ? 'Pembayaran Gagal' : 'Belum Dibayar'))
                                    }}
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
                                                    @elseif($payment->payment_method === 'midtrans')
                                                        Midtrans
                                                    @else
                                                        {{ $payment->payment_method }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ 
                                                        $payment->status === 'success' ? 'success' : 
                                                        ($payment->status === 'pending' ? 'warning' : 'danger') 
                                                    }}">
                                                        {{ 
                                                            $payment->status === 'success' ? 'Berhasil' : 
                                                            ($payment->status === 'pending' ? 'Menunggu' : 'Gagal') 
                                                        }}
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
                                        <button class="btn btn-white btn-sm" onclick="payNow({{ $rental->id }})">
                                            Bayar Sekarang
                                        </button>
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

    <!-- Midtrans Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
    function payNow(rentalId) {
        fetch(`/payments/start/${rentalId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    window.location.href = '/customer/payments/success';
                },
                onPending: function(result) {
                    window.location.href = '/customer/payments/pending';
                },
                onError: function(result) {
                    window.location.href = '/customer/payments/error';
                },
                onClose: function() {
                    alert('Pembayaran dibatalkan');
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pembayaran');
        });
    }
    </script>

    @if($rental->rental_status === 'confirmed' && $rental->payment_status !== 'paid')
        <div class="card mt-4">
            <div class="card-header">
                <h6>Pilih Metode Pembayaran</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <button class="btn btn-primary w-100 mb-3" onclick="payNow({{ $rental->id }})">
                            <i class="fas fa-credit-card"></i> Bayar dengan Midtrans
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#manualPaymentModal">
                            <i class="fas fa-upload"></i> Upload Bukti Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Upload Bukti Transfer -->
        <div class="modal fade" id="manualPaymentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('customer.payments.pay', $rental) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Bukti Transfer</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label>Jumlah Pembayaran</label>
                                <input type="number" name="amount" class="form-control" required 
                                    min="1" max="{{ $rental->total_price - $rental->payments->where('status', 'success')->sum('amount') }}">
                                <small class="text-muted">Sisa yang harus dibayar: Rp {{ number_format($rental->total_price - $rental->payments->where('status', 'success')->sum('amount'), 0, ',', '.') }}</small>
                            </div>
                            <div class="form-group mb-3">
                                <label>Bukti Transfer</label>
                                <input type="file" name="payment_proof" class="form-control" required accept="image/*">
                                <small class="text-muted">Format: JPG, PNG (max 2MB)</small>
                            </div>
                            <div class="form-group">
                                <label>Catatan (Opsional)</label>
                                <textarea name="notes" class="form-control" rows="2"></textarea>
                            </div>
                            <input type="hidden" name="payment_method" value="transfer">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Kirim Bukti Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection