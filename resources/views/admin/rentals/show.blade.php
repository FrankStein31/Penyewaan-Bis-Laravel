@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Detail Penyewaan'])
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="card shadow-lg">
                    <div class="card-header p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h5 class="mb-0 text-primary fw-bold">Detail Penyewaan</h5>
                                <p class="text-sm mb-0">Kode Sewa: {{ $rental->rental_code }}</p>
                            </div>
                            <div>
                                <form id="updateStatusForm" action="{{ route('admin.rentals.update-status', $rental) }}" 
                                    method="POST" 
                                    class="d-flex align-items-center gap-3">
                                    @csrf
                                    @method('PUT')
                                    <select name="rental_status" id="rentalStatus" class="form-select form-select-sm bg-white" style="min-width: 140px; border-radius: 0.5rem;">
                                        <option value="pending" {{ $rental->rental_status == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                                        <option value="confirmed" {{ $rental->rental_status == 'confirmed' ? 'selected' : '' }}>Konfirmasi</option>
                                        <option value="ongoing" {{ $rental->rental_status == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                        <option value="completed" {{ $rental->rental_status == 'completed' ? 'selected' : '' }}>Selesai</option>
                                        <option value="cancelled" {{ $rental->rental_status == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    <button type="button" 
                                            onclick="confirmStatusUpdate()" 
                                            class="btn bg-gradient-primary btn-sm mb-0" 
                                            style="border-radius: 0.5rem; width: 100%;">
                                        Perbarui Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4">
                        <!-- Status and Date Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-{{ 
                                            $rental->rental_status === 'pending' ? 'warning' : 
                                            ($rental->rental_status === 'confirmed' ? 'info' :
                                            ($rental->rental_status === 'ongoing' ? 'primary' :
                                            ($rental->rental_status === 'completed' ? 'success' : 'danger'))) 
                                        }} p-2">
                                            {{ ucfirst($rental->rental_status) === 'Pending' ? 'Menunggu Konfirmasi' :
                                               (ucfirst($rental->rental_status) === 'Confirmed' ? 'Dikonfirmasi' :
                                               (ucfirst($rental->rental_status) === 'Ongoing' ? 'Sedang Berlangsung' :
                                               (ucfirst($rental->rental_status) === 'Completed' ? 'Selesai' : 'Dibatalkan'))) }}
                                        </span>
                                    </div>
                                    <div>
                                        <small class="text-muted">Tanggal Pemesanan:</small>
                                        <p class="mb-0 fw-bold">{{ $rental->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">Informasi Pelanggan</h6>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Nama Lengkap</small>
                                            <span class="fw-bold">{{ $rental->user->firstname }} {{ $rental->user->lastname }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Email</small>
                                            <span>{{ $rental->user->email }}</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">No. Telepon</small>
                                            <span>{{ $rental->user->phone }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bus Info -->
                            <div class="col-md-6">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">Informasi Bus</h6>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">No Plat Bus</small>
                                            <span class="fw-bold">{{ $rental->bus->plate_number }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Tipe Bus</small>
                                            <span>{{ $rental->bus->type }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Kapasitas</small>
                                            <span>{{ $rental->bus->capacity }} Seat</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Armada</small>
                                            <span>{{ $rental->bus->armada->nama_armada }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Crew Info -->
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <div class="card border shadow-none">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">Informasi Crew</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted d-block mb-2">Driver</small>
                                                @if($rental->driver)
                                                    <div class="d-flex align-items-center">
                                                        @if($rental->driver->photo)
                                                            <img src="{{ Storage::url('drivers/'.$rental->driver->photo) }}" 
                                                                 class="avatar avatar-sm rounded-circle me-2" 
                                                                 alt="Driver Photo">
                                                        @endif
                                                        <div>
                                                            <span class="fw-bold d-block">{{ $rental->driver->name }}</span>
                                                            <small class="text-muted">HP: {{ $rental->driver->phone }}</small>
                                                            <small class="text-muted d-block">
                                                                SIM: {{ $rental->driver->license_number }}
                                                                (Exp: {{ date('d/m/Y', strtotime($rental->driver->license_expire)) }})
                                                            </small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Tidak ada</span>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted d-block mb-2">Kondektur</small>
                                                @if($rental->conductor)
                                                    <div class="d-flex align-items-center">
                                                        @if($rental->conductor->photo)
                                                            <img src="{{ asset('storage/conductors/' . $rental->conductor->photo) }}" 
                                                                 class="avatar avatar-sm rounded-circle me-2" 
                                                                 alt="Conductor Photo">
                                                        @endif
                                                        <div>
                                                            <span class="fw-bold d-block">{{ $rental->conductor->name }}</span>
                                                            <small class="text-muted">HP: {{ $rental->conductor->phone }}</small>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Tidak ada</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trip and Payment Info -->
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">Informasi Perjalanan</h6>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Lokasi Penjemputan</small>
                                            <span>{{ $rental->pickup_location }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Tujuan</small>
                                            <span>{{ $rental->destination }}</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Periode Sewa</small>
                                            <span>{{ date('d/m/Y', strtotime($rental->start_date)) }} - {{ date('d/m/Y', strtotime($rental->end_date)) }}</span>
                                            <small class="text-muted d-block">Total: {{ $rental->total_days }} hari</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border shadow-none">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">Informasi Pembayaran</h6>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Status Pembayaran</small>
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
                                            <span class="badge badge-sm bg-gradient-{{ 
                                                $rental->payment_status === 'unpaid' ? 'danger' :
                                                ($rental->payment_status === 'partial' ? 'warning' : 'success')
                                            }}">
                                                {{ 
                                                    $rental->payment_status === 'unpaid' ? 'Belum Lunas' :
                                                    ($rental->payment_status === 'partial' ? 'Pembayaran Sebagian' : 'Lunas')
                                                }}
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block">Total Harga</small>
                                            <span class="fw-bold">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</span>
                                        </div>
                                        @if($rental->payment)
                                            <div class="mb-2">
                                                <small class="text-muted d-block">DP</small>
                                                <span>Rp {{ number_format($rental->payment->down_payment, 0, ',', '.') }}</span>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Sisa Pembayaran</small>
                                                <span class="text-danger">Rp {{ number_format($rental->total_price - $rental->payment->down_payment, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Extension History -->
                        <div class="row g-4 mb-4">
                            <div class="col-12">
                                <div class="card border shadow-none">
                                    <div class="card-body">
                                        <h6 class="text-primary fw-bold mb-3">Riwayat Perpanjangan</h6>
                                        @if($rental->extensions->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table align-items-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tanggal Pengajuan</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Periode</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tambahan Hari</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Biaya</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status Pengajuan</th>
                                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status Pembayaran</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($rental->extensions as $extension)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">{{ $extension->created_at->format('d/m/Y') }}</h6>
                                                                        <p class="text-xs text-secondary mb-0">{{ $extension->created_at->format('H:i') }}</p>
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
                                                            </td>
                                                            <td>
                                                                @if($extension->status === 'approved')
                                                                    <span class="badge badge-sm bg-gradient-{{ 
                                                                        $extension->payment_status === 'pending' ? 'warning' : 
                                                                        ($extension->payment_status === 'paid' ? 'success' : 'danger') 
                                                                    }}">
                                                                        {{ $extension->payment_status === 'pending' ? 'Belum Dibayar' :
                                                                        ($extension->payment_status === 'paid' ? 'Sudah Dibayar' : 'Pembayaran Ditolak') }}
                                                                    </span>
                                                                    @if($extension->payment_status === 'paid')
                                                                        <p class="text-xs text-secondary mb-0 mt-1">
                                                                            Dibayar: {{ $extension->paid_at ? $extension->paid_at->format('d/m/Y H:i') : '-' }}
                                                                        </p>
                                                                    @endif
                                                                @else
                                                                    <span class="text-xs text-secondary">-</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-sm text-secondary mb-0">Belum ada riwayat perpanjangan</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer')
    </div>
@endsection

<!-- @push('js') -->
<script>
function confirmStatusUpdate() {
    const newStatus = document.getElementById('rentalStatus').value;
    const currentStatus = "{{ $rental->rental_status }}";
    const paymentStatus = "{{ $rental->payment_status }}";
    
    let warningMessage = '';
    if (newStatus === 'ongoing') {
        if (paymentStatus === 'unpaid') {
            warningMessage = 'Status tidak dapat diubah ke ongoing karena belum ada pembayaran sama sekali!';
        } else if (paymentStatus === 'partial') {
            warningMessage = 'Perhatian: Masih ada sisa pembayaran yang belum lunas. Yakin ingin melanjutkan?';
        }
    } else if (newStatus === 'cancelled') {
        warningMessage = 'Pesanan yang dibatalkan tidak dapat dikembalikan ke status sebelumnya.';
    } else if (newStatus === 'completed') {
        warningMessage = 'Pastikan perjalanan sudah selesai sebelum mengubah status menjadi completed.';
    }

    if (warningMessage && paymentStatus === 'unpaid' && newStatus === 'ongoing') {
        Swal.fire({
            icon: 'error',
            title: 'Tidak dapat mengubah status',
            text: warningMessage
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Update Status',
        html: `Anda yakin ingin mengubah status dari <b>${currentStatus}</b> menjadi <b>${newStatus}</b>?` + 
              (warningMessage ? `<br><br><small class="text-warning">${warningMessage}</small>` : ''),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('updateStatusForm').submit();
        }
    });
}

// Tampilkan alert jika ada pesan dari controller
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: "{{ session('error') }}"
    });
@endif
</script>
<!-- @endpush -->