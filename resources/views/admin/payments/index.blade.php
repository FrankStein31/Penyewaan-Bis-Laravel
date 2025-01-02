@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Pembayaran'])
    <div class="container-fluid py-4">
        <!-- Rental yang Belum Lunas -->
        <div class="row">
            <div class="col-12">
                <div class="card card-frame mb-4">
                    <div class="card-header p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Daftar Rental yang Belum Lunas</h5>
                                <p class="text-sm mb-0 text-muted">Daftar rental yang masih memiliki sisa pembayaran</p>
                            </div>
                            <div class="ms-auto text-end">
                                <span class="badge badge-sm bg-gradient-warning">
                                    {{ count($unpaidRentals) }} Rental Belum Lunas
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-3 pt-0 pb-3">
                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0" id="unpaidTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Tanggal</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Kode Rental</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Customer</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Bus</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Total Harga</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Sudah Dibayar</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unpaidRentals as $rental)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $rental['created_at']->format('d/m/Y') }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $rental['created_at']->format('H:i') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.rentals.show', $rental['rental_id']) }}" 
                                                   class="text-primary font-weight-bold text-sm">
                                                    {{ $rental['rental_code'] }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $rental['customer_name'] }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $rental['bus_plate'] }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-dark text-sm font-weight-bold">
                                                    Rp {{ number_format($rental['total_price'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success text-sm font-weight-bold">
                                                    Rp {{ number_format($rental['total_paid'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-danger text-sm font-weight-bold">
                                                    Rp {{ number_format($rental['remaining_amount'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-frame mb-4">
                    <div class="card-header p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Riwayat Pembayaran</h5>
                                <p class="text-sm mb-0 text-muted">Daftar seluruh transaksi pembayaran</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-3 pt-0 pb-3">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mx-2" role="alert">
                                <span class="alert-icon"><i class="ni ni-check-bold"></i></span>
                                <span class="alert-text">{{ session('success') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mx-2" role="alert">
                                <span class="alert-icon"><i class="ni ni-bold-x"></i></span>
                                <span class="alert-text">{{ session('error') }}</span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0" id="paymentsTable">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Tanggal</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Kode Rental</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Customer</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Bus</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Jumlah</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Metode</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Status</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Bukti</th>
                                        <th class="text-uppercase text-dark text-xs font-weight-bold opacity-9">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $payment['created_at']->format('d/m/Y') }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $payment['created_at']->format('H:i') }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.rentals.show', $payment['rental_id']) }}" 
                                                   class="text-primary font-weight-bold text-sm">
                                                    {{ $payment['rental_code'] }}
                                                </a>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $payment['customer_name'] }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-sm font-weight-bold">{{ $payment['bus_plate'] }}</span>
                                            </td>
                                            <td>
                                                <span class="text-dark text-sm font-weight-bold">
                                                    Rp {{ number_format($payment['amount'], 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-info">
                                                    {{ ucfirst($payment['payment_method']) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $payment['status'] === 'success' ? 'success' : 
                                                    ($payment['status'] === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment['status']) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($payment['proof'])
                                                    <a href="{{ Storage::url($payment['proof']) }}" 
                                                       target="_blank" 
                                                       class="btn btn-link text-info text-sm mb-0">
                                                        <i class="fas fa-file-image me-1"></i>
                                                        Lihat Bukti
                                                    </a>
                                                @else
                                                    <span class="text-xs text-secondary">Tidak ada</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment['status'] === 'pending')
                                                    <form action="{{ route('admin.payments.verify', $payment['id']) }}" 
                                                          method="POST" 
                                                          class="d-inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" 
                                                                class="btn btn-link text-success text-sm mb-0"
                                                                onclick="return confirm('Verifikasi pembayaran ini?')">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Verifikasi
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        const tableConfig = {
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
            },
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
            dom: '<"d-flex justify-content-between align-items-center mb-3"lf>rt<"d-flex justify-content-between align-items-center"ip>',
            initComplete: function() {
                $('.dataTables_filter input').addClass('form-control form-control-sm');
                $('.dataTables_length select').addClass('form-select form-select-sm');
            }
        };

        $('#unpaidTable').DataTable(tableConfig);
        $('#paymentsTable').DataTable(tableConfig);
    });
</script>
@endpush