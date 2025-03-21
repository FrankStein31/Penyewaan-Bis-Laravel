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
                                    <div class="mb-3 text-center">
                                        <div id="countdown" class="text-danger">
                                            <span class="badge bg-danger"><i class="fas fa-clock me-1"></i> Sisa waktu: <span id="timer" class="fw-bold">00:15:00</span></span>
                                        </div>
                                    </div>
                                    <button class="btn btn-success w-100" onclick="payNow({{ $rental->id }})">
                                        <i class="fas fa-credit-card me-2"></i> Bayar Sekarang
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
                                    {{ $rental->rental_status === 'pending' ? 'Menunggu Konfirmasi' :
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
        @if($rental->rental_status === 'confirmed' && $rental->payment_status !== 'paid')
        <div class="card mt-4">
            <div class="card-header">
                <h6>Pembayaran Manual</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6>Total yang harus dibayar:</h6>
                    <h4>Rp {{ number_format($rental->total_price, 0, ',', '.') }}</h4>
                    <hr>
                    <h6>Informasi Rekening:</h6>
                    <p class="mb-0">Bank BCA: 1234567890 <br> a.n PT Sewa Bus</p>
                </div>
                
                <form action="{{ route('customer.rentals.pay', $rental) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Jumlah Pembayaran</label>
                        <input type="number" name="amount" class="form-control" required 
                               min="1" max="{{ $rental->total_price }}"
                               value="{{ $rental->total_price }}">
                    </div>
                    <div class="form-group mb-3">
                        <label>Bukti Transfer</label>
                        <input type="file" name="payment_proof" class="form-control" required accept="image/*">
                        <small class="text-muted">Format: JPG, PNG (max 2MB)</small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control" rows="2"></textarea>
                    </div>
                    <input type="hidden" name="payment_method" value="transfer">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-upload"></i> Kirim Bukti Pembayaran
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($rental->extensions()->exists())
            <div class="card mt-4">
                <div class="card-header">
                    <h6>Riwayat Perpanjangan</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pengajuan</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Periode</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tambahan Hari</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Biaya</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rental->extensions as $extension)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $extension->created_at->format('d/m/Y H:i') }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                {{ $extension->start_date->format('d/m/Y') }} - 
                                                {{ $extension->end_date->format('d/m/Y') }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $extension->additional_days }} hari</p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">
                                                Rp {{ number_format($extension->additional_price, 0, ',', '.') }}
                                            </p>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $extension->status === 'pending' ? 'warning' : 
                                                ($extension->status === 'approved' ? 'success' : 'danger') 
                                            }}">
                                                {{ $extension->status === 'pending' ? 'Menunggu' :
                                                ($extension->status === 'approved' ? 'Disetujui' : 'Ditolak') }}
                                            </span>
                                            @if($extension->status === 'approved')
                                                <br>
                                                <span class="badge badge-sm bg-gradient-{{ 
                                                    $extension->payment_status === 'pending' ? 'warning' : 
                                                    ($extension->payment_status === 'paid' ? 'success' : 'danger') 
                                                }}">
                                                    {{ $extension->payment_status === 'pending' ? 'Belum Dibayar' :
                                                    ($extension->payment_status === 'paid' ? 'Sudah Dibayar' : 'Pembayaran Ditolak') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($extension->status === 'approved' && $extension->payment_status === 'pending')
                                                <button class="btn btn-primary btn-sm" 
                                                        onclick="showPaymentOptionsModal({{ $extension->id }}, {{ $extension->additional_price }})">
                                                    <i class="fas fa-credit-card me-2"></i>Bayar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        @if($rental->rental_status === 'ongoing')
            <div class="card mt-4">
                <div class="card-header">
                    <h6>Ajukan Perpanjangan</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('customer.rentals.request-extension', $rental) }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label>Tanggal Mulai Perpanjangan</label>
                            <input type="datetime-local" name="start_date" class="form-control" required 
                                   onchange="calculateExtensionPrice(this)">
                        </div>
                        <div class="form-group mb-3">
                            <label>Tanggal Selesai Perpanjangan</label>
                            <input type="datetime-local" name="end_date" class="form-control" required
                                   onchange="calculateExtensionPrice(this)">
                        </div>
                        <div class="form-group mb-3">
                            <label>Biaya Perpanjangan</label>
                            <div class="form-control-static">
                                <h5 id="extensionPrice">Rp 0</h5>
                                <small class="text-muted">Harga per hari: Rp {{ number_format($rental->bus->price_per_day, 0, ',', '.') }}</small>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Ajukan Perpanjangan</button>
                    </form>
                </div>
            </div>

            <script>
            function calculateExtensionPrice() {
                const startDate = document.querySelector('input[name="start_date"]').value;
                const endDate = document.querySelector('input[name="end_date"]').value;
                
                if (startDate && endDate) {
                    const start = new Date(startDate);
                    const end = new Date(endDate);
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    const pricePerDay = {{ $rental->bus->price_per_day }};
                    const totalPrice = diffDays * pricePerDay;
                    
                    document.getElementById('extensionPrice').textContent = 
                        'Rp ' + totalPrice.toLocaleString('id-ID');
                }
            }
            </script>
        @endif

        

        @include('layouts.footers.auth.footer')
    </div>

    <!-- Midtrans Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

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
                    // Kirim notifikasi ke server untuk mengirim email
                    updatePaymentStatus(rentalId, 'success', result);
                    window.location.href = '/customer/payments/success';
                },
                onPending: function(result) {
                    // Kirim notifikasi ke server untuk mengirim email
                    updatePaymentStatus(rentalId, 'pending', result);
                    window.location.href = '/customer/payments/pending';
                },
                onError: function(result) {
                    // Kirim notifikasi ke server untuk mengirim email
                    updatePaymentStatus(rentalId, 'error', result);
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

    // Fungsi untuk mengirim status pembayaran ke server
    function updatePaymentStatus(rentalId, status, result) {
        fetch(`/payments/midtrans-status-update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                rental_id: rentalId,
                status: status,
                result: result
            })
        });
    }

    let currentExtensionId;

    function showPaymentOptionsModal(extensionId, amount) {
        currentExtensionId = extensionId;
        document.getElementById('extensionIdInput').value = extensionId;
        document.getElementById('paymentAmount').textContent = 
            'Rp ' + parseInt(amount).toLocaleString('id-ID');
        
        document.getElementById('uploadForm').action = 
            `/customer/rentals/extensions/${extensionId}/pay`;
        
        const modal = new bootstrap.Modal(document.getElementById('paymentOptionsModal'));
        modal.show();
    }

    function showUploadForm() {
        // Tutup modal pilihan pembayaran
        bootstrap.Modal.getInstance(document.getElementById('paymentOptionsModal')).hide();
        
        // Tampilkan modal upload
        const uploadModal = new bootstrap.Modal(document.getElementById('uploadModal'));
        uploadModal.show();
    }

    function payWithMidtrans() {
        fetch(`/payments/start/${currentExtensionId}`, {
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

    <!-- Modal Pilihan Pembayaran Perpanjangan -->
    <div class="modal fade" id="paymentOptionsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Metode Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6>Total Pembayaran:</h6>
                        <h4 id="paymentAmount"></h4>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary" onclick="payWithMidtrans()">
                            <i class="fas fa-credit-card"></i> Bayar dengan Midtrans
                        </button>
                        <button class="btn btn-secondary" onclick="showUploadForm()">
                            <i class="fas fa-upload"></i> Upload Bukti Transfer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Bukti Transfer -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6>Informasi Rekening:</h6>
                        <p class="mb-0">Bank BCA: 1234567890 <br> a.n PT Sewa Bus</p>
                    </div>
                    <form id="uploadForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="extension_id" value="" id="extensionIdInput">
                        <div class="form-group mb-3">
                            <label>Bukti Transfer</label>
                            <input type="file" name="payment_proof" class="form-control" required accept="image/*">
                            <small class="text-muted">Format: JPG, PNG (max 2MB)</small>
                        </div>
                        <div class="form-group mb-3">
                            <label>Catatan (Opsional)</label>
                            <textarea name="notes" class="form-control" rows="2"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-upload"></i> Kirim Bukti Pembayaran
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const confirmedAt = new Date('{{ $rental->updated_at }}').getTime();
            const deadline = confirmedAt + (24 * 60 * 60 * 1000); // 24 jam
            
            function updateTimer() {
                const now = new Date().getTime();
                const distance = deadline - now;
                
                if (distance < 0) {
                    document.getElementById('timer').innerHTML = 'Waktu habis';
                    cancelUnpaidRental();
                    return;
                }
                
                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById('timer').innerHTML = 
                    hours + "j " + minutes + "m " + seconds + "d";
            }
            
            function cancelUnpaidRental() {
                fetch(`/rentals/cancel-unpaid/{{ $rental->id }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
            }
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    </script>
@endsection


