@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Form Pembayaran</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="mb-2">Detail Rental</h6>
                            <p class="text-sm mb-1">Kode Rental: {{ $rental->rental_code }}</p>
                            <p class="text-sm mb-1">Bus: {{ $rental->bus->name }}</p>
                            <p class="text-sm mb-1">Total Harga: Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                            <p class="text-sm mb-1">Sudah Dibayar: Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                            <p class="text-sm mb-1">Sisa Pembayaran: Rp {{ number_format($remainingAmount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('customer.payments.pay', $rental) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Jumlah Pembayaran</label>
                                    <input type="number" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" 
                                           name="amount" 
                                           min="1" 
                                           max="{{ $remainingAmount }}"
                                           value="{{ old('amount', $remainingAmount) }}"
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Maksimal pembayaran: Rp {{ number_format($remainingAmount, 0, ',', '.') }}</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_method">Metode Pembayaran</label>
                                    <select class="form-control @error('payment_method') is-invalid @enderror" 
                                            id="payment_method" 
                                            name="payment_method" 
                                            required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                        <option value="transfer">Transfer Bank</option>
                                        <option value="cash">Tunai</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group" id="proofGroup">
                                    <label for="payment_proof">Bukti Pembayaran</label>
                                    <input type="file" 
                                           class="form-control @error('payment_proof') is-invalid @enderror" 
                                           id="payment_proof" 
                                           name="payment_proof"
                                           accept="image/*">
                                    @error('payment_proof')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Upload bukti pembayaran (format: JPG, PNG, max: 2MB)</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Proses Pembayaran</button>
                                <a href="{{ route('customer.payments') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>  
@endsection

@push('scripts')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" 
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethod = document.getElementById('payment_method');
    const proofGroup = document.getElementById('proofGroup');
    const paymentProof = document.getElementById('payment_proof');

    function toggleProofInput() {
        if (paymentMethod.value === 'transfer') {
            proofGroup.style.display = 'block';
            paymentProof.required = true;
        } else {
            proofGroup.style.display = 'none';
            paymentProof.required = false;
        }
    }

    paymentMethod.addEventListener('change', toggleProofInput);
    toggleProofInput(); // Set initial state
});

document.getElementById('pay-button').onclick = function() {
    fetch(`/payments/${rentalId}/get-snap-token`)
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                alert(data.error);
                return;
            }
            
            snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    window.location.href = '/payments/success';
                },
                onPending: function(result) {
                    window.location.href = '/payments/pending';
                },
                onError: function(result) {
                    window.location.href = '/payments/error';
                },
                onClose: function() {
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses pembayaran');
        });
};
</script>
@endpush 