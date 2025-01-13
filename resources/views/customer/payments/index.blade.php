@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pembayaran'])
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Pembayaran yang Perlu Diselesaikan</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($activeRentals->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-sm mb-0">Tidak ada pembayaran yang perlu diselesaikan</p>
                            </div>
                        @else
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Kode Rental</th>
                                            <th>Bus</th>
                                            <th>Total Harga</th>
                                            <th>Sudah Dibayar</th>
                                            <th>Sisa Pembayaran</th>
                                            <th>Pembayaran</th>
                                            <!-- <th>Status Pembayaran</th> -->
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activeRentals as $data)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1">
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm">#{{ $data['rental']->rental_code }}</h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $data['rental']->created_at->format('d/m/Y H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($data['rental']->bus->photo)
                                                            <img src="{{ Storage::url($data['rental']->bus->photo) }}" 
                                                                 class="avatar avatar-sm rounded-circle me-2">
                                                        @else
                                                            <div class="icon-wrapper bg-gradient-primary text-white me-2">
                                                                <i class="fas fa-bus"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-sm font-weight-bold mb-0">{{ $data['rental']->bus->plate_number }}</p>
                                                            <p class="text-xs text-secondary mb-0">Type: {{ $data['rental']->bus->type }}</p>
                                                            <p class="text-xs text-secondary mb-0">Kapasitas: {{ $data['rental']->bus->capacity }} Seat</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        Rp {{ number_format($data['rental']->total_price, 0, ',', '.') }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        Rp {{ number_format($data['total_paid'], 0, ',', '.') }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        Rp {{ number_format($data['remaining_amount'], 0, ',', '.') }}
                                                    </p>
                                                </td>
                                                <td>
                                                    @if($data['rental']->payments->where('status', 'pending')->count() > 0)
                                                        <span class="badge badge-sm bg-gradient-warning">
                                                            Menunggu Konfirmasi
                                                        </span>
                                                    @else
                                                        <span class="badge badge-sm bg-gradient-{{ 
                                                            $data['rental']->payment_status == 'paid' ? 'success' : 
                                                            ($data['rental']->payment_status == 'partially_paid' ? 'info' : 'danger') 
                                                        }}">
                                                            {{ 
                                                                $data['rental']->payment_status == 'paid' ? 'Lunas' :
                                                                ($data['rental']->payment_status == 'partially_paid' ? 'Dibayar Sebagian' : 'Belum Dibayar')
                                                            }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($data['rental']->rental_status === 'confirmed' && $data['rental']->payment_status !== 'paid')
                                                        <button class="btn btn-sm bg-gradient-info text-white px-3 mb-0" 
                                                                onclick="payNow({{ $data['rental']->id }})">
                                                            <i class="fas fa-money-bill me-2"></i>Bayar
                                                        </button>
                                                    @else
                                                        <span class="text-xs text-secondary">Tidak ada aksi</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Riwayat Pembayaran</h6>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        @if($paymentHistory->isEmpty())
                            <div class="text-center py-4">
                                <p class="text-sm mb-0">Belum ada riwayat pembayaran</p>
                            </div>
                        @else
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode Rental</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Bus</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Jumlah</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Metode</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($paymentHistory as $rental)
                                            @foreach($rental->payments as $payment)
                                                <tr>
                                                    <td class="align-middle text-sm">
                                                        <p class="text-sm font-weight-bold mb-0">{{ $payment->created_at->format('d/m/Y H:i') }}</p>
                                                    </td>
                                                    <td class="align-middle text-sm">
                                                        <p class="text-sm font-weight-bold mb-0">{{ $rental->rental_code }}</p>
                                                    </td>
                                                    <td class="align-middle text-sm">
                                                        <p class="text-sm font-weight-bold mb-0">{{ $rental->bus->name }}</p>
                                                    </td>
                                                    <td class="align-middle text-sm">
                                                        <p class="text-sm font-weight-bold mb-0">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                                    </td>
                                                    <td class="align-middle text-sm">
                                                        <p class="text-sm font-weight-bold mb-0">{{ $payment->payment_method }}</p>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge badge-sm bg-gradient-success">Success</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Form Pembayaran Cepat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="quickPaymentForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="amount">Jumlah Pembayaran</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                            <small class="text-muted">Sisa yang harus dibayar: <span id="remainingAmount"></span></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="payment_method">Metode Pembayaran</label>
                            <select class="form-control" id="payment_method" name="payment_method" required>
                                <option value="">Pilih Metode Pembayaran</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="cash">Tunai</option>
                            </select>
                        </div>
                        <div class="form-group mb-3" id="proofGroup" style="display: none;">
                            <label for="payment_proof">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="payment_proof" name="payment_proof" accept="image/*">
                            <small class="text-muted">Upload bukti pembayaran (format: JPG, PNG, max: 2MB)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Midtrans Script -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

    <script>
    function setupQuickPayment(rentalId, remainingAmount) {
        const form = document.getElementById('quickPaymentForm');
        const amountInput = document.getElementById('amount');
        const remainingAmountSpan = document.getElementById('remainingAmount');
        const paymentMethod = document.getElementById('payment_method');
        const proofGroup = document.getElementById('proofGroup');
        const paymentProof = document.getElementById('payment_proof');

        // Set form action
        form.action = `/payments/${rentalId}/pay`;
        
        // Set max amount dan tampilkan sisa pembayaran
        amountInput.max = remainingAmount;
        amountInput.value = remainingAmount;
        remainingAmountSpan.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(remainingAmount)}`;

        // Reset form
        form.reset();
        paymentMethod.value = '';
        proofGroup.style.display = 'none';
        paymentProof.required = false;

        // Event listener untuk metode pembayaran
        paymentMethod.addEventListener('change', function() {
            if (this.value === 'transfer') {
                proofGroup.style.display = 'block';
                paymentProof.required = true;
            } else {
                proofGroup.style.display = 'none';
                paymentProof.required = false;
            }
        });
    }

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
@endsection