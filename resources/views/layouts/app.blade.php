<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PO Bis Ekasari') }}</title>
    
    <!-- Favicon -->
    <link href="https://demos.creative-tim.com/argon-dashboard/assets/img/favicon.png" rel="icon" type="image/png">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    
    <!-- Icons -->
    <!-- Keep only these -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard/assets/css/nucleo-icons.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard/assets/css/nucleo-svg.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    
    <!-- CSS Files -->
    <link id="pagestyle" href="https://demos.creative-tim.com/argon-dashboard/assets/css/argon-dashboard.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        /* Custom styles */
        .sidenav {
            width: 250px;
            padding: 0.5rem;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .dashboard-card {
            padding: 1.5rem;
            border-radius: 8px;
            color: white;
            margin-bottom: 1rem;
        }
        
        .card-total-bis { background: #0d6efd; }
        .card-tersedia { background: #198754; }
        .card-disewa { background: #ffc107; }
        .card-maintenance { background: #17a2b8; }
        
        .dashboard-card h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }
        
        .dashboard-card p {
            margin: 0;
            opacity: 0.8;
        }
        
        .table-container {
            background: white;
            border-radius: 8px;
            padding: 1rem;
            margin-top: 2rem;
        }
        
        .table-title {
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: #f8f9fa;
            border-radius: 4px;
        }

        /* Tambahan style untuk icon */
        .icon i {
            font-size: 1rem;
            width: 1.5rem;
            height: 1.5rem;
            line-height: 1.5rem;
            text-align: center;
        }

        .nav-link .icon {
            background: #f6f9fc;
            border-radius: 0.5rem;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
        }

        .nav-link.active .icon {
            background: #5e72e4;
        }

        .nav-link.active .icon i {
            color: white !important;
        }
        
        .icon-shape {
            min-width: 32px;
            width: 32px !important;
            height: 32px !important;
            background-color: #f6f9fc;
            display: flex !important;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .icon-shape i {
            font-size: 1rem;
            color: #5e72e4;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-link.active .icon-shape {
            background-color: #5e72e4;
        }

        .nav-link.active .icon-shape i {
            color: white !important;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            margin: 0.1rem 0.8rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            height: 44px;
        }
        
        .nav-link:hover {
            background-color: #f6f9fc;
        }

        .nav-link.active {
            background-color: #f6f9fc;
        }

        .nav-link.active .icon-shape {
            background-color: #5e72e4;
        }

        .nav-link.active .icon-shape i {
            color: white !important;
        }
        
        .nav-link-text {
            font-size: 0.875rem;
            font-weight: 500;
            color: #67748e;
            margin-left: 1rem;
            display: flex;
            align-items: center;
        }

        .nav-link.active .nav-link-text {
            color: #344767;
            font-weight: 600;
        }
        
        .icon-shape i.ni {
            line-height: 1;
            font-weight: 600;
        }

        .navbar-nav {
            padding: 0.5rem;
        }

        /* Penyesuaian untuk form logout */
        form.nav-link {
            margin: 0 0.8rem;
        }

        form.nav-link a {
            display: flex;
            align-items: center;
            text-decoration: none;
            width: 100%;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    @auth
    <!-- Sidebar -->
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="#">
                <div class="d-flex align-items-center">
                    <i class="fas fa-bus fa-2x"></i>
                    <span class="ms-1 font-weight-bold">PO Bis Ekasari</span>
                </div>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        
        <div class="collapse navbar-collapse w-auto h-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                @if(auth()->user()->role == 'admin')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-tv"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/drivers*') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="nav-link-text ms-1">Data Supir</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/conductors*') ? 'active' : '' }}" href="{{ route('admin.conductors.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="nav-link-text ms-1">Data Kernet</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/armada*') ? 'active' : '' }}" href="{{ route('admin.armada.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-building"></i>
                            </div>
                            <span class="nav-link-text ms-1">Jenis Armada</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/buses*') ? 'active' : '' }}" href="{{ route('admin.buses.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-bus"></i>
                            </div>
                            <span class="nav-link-text ms-1">Data Bis</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/customers*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-users"></i>
                            </div>
                            <span class="nav-link-text ms-1">Data Pelanggan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/rentals*') ? 'active' : '' }}" href="{{ route('admin.rentals.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span class="nav-link-text ms-1">Transaksi Penyewaan</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/bus-status') ? 'active' : '' }}" href="{{ route('admin.buses.status') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span class="nav-link-text ms-1">Status Bis</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/payments*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="nav-link-text ms-1">Pembayaran</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/midtrans*') ? 'active' : '' }}" href="{{ route('admin.midtrans.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-money-check-alt"></i>
                            </div>
                            <span class="nav-link-text ms-1">Midtrans</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/requests*') ? 'active' : '' }}" href="{{ route('admin.requests.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-bell"></i>
                            </div>
                            <span class="nav-link-text ms-1">Pengajuan</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('admin/rentals/extensions*') ? 'active' : '' }}" 
                        href="{{ route('admin.rentals.extensions') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-clock text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Perpanjangan Sewa</span>
                        </a>
                    </li>
                    
                @elseif(auth()->user()->role == 'owner')
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-tv"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/statistics/daily') ? 'active' : '' }}" href="{{ route('owner.statistics.daily') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span class="nav-link-text ms-1">Statistik Harian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/statistics/monthly') ? 'active' : '' }}" href="{{ route('owner.statistics.monthly') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <span class="nav-link-text ms-1">Statistik Bulanan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/statistics/yearly') ? 'active' : '' }}" href="{{ route('owner.statistics.yearly') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <span class="nav-link-text ms-1">Statistik Tahunan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/statistics/bus') ? 'active' : '' }}" href="{{ route('owner.statistics.bus') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-bus"></i>
                            </div>
                            <span class="nav-link-text ms-1">Statistik Penggunaan Bis</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/statistics/driver') ? 'active' : '' }}" href="{{ route('owner.statistics.driver') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="nav-link-text ms-1">Jam Terbang Supir</span>
                        </a>
                        
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/statistics/fleet') ? 'active' : '' }}" href="{{ route('owner.statistics.fleet') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <span class="nav-link-text ms-1">Ranking Armada</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('owner/users*') ? 'active' : '' }}" href="{{ route('owner.users.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <span class="nav-link-text ms-1">Manajemen User</span>
                        </a>
                    </li>
                    
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-tv"></i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/search*') ? 'active' : '' }}" href="{{ route('customer.search') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-search"></i>
                            </div>
                            <span class="nav-link-text ms-1">Cari & Pesan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('rentals*') ? 'active' : '' }}" href="{{ route('rentals.index') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-calendar-alt text-primary text-sm opacity-10"></i>
                            </div>
                            <span class="nav-link-text ms-1">Penyewaan Saya</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/payments*') ? 'active' : '' }}" href="{{ route('customer.payments') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="nav-link-text ms-1">Pembayaran</span>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/ratings*') ? 'active' : '' }}" href="{{ route('customer.ratings') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="nav-link-text ms-1">Beri Rating</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('customer/profile*') ? 'active' : '' }}" href="{{ route('customer.profile.edit') }}">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="nav-link-text ms-1">Profile</span>
                        </a>
                    </li>
                @endif
                <!-- <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}" class="nav-link">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span class="nav-link-text ms-1">Logout</span>
                        </a>
                    </form>
                </li> -->
            </ul>
            
            <!-- Spacer untuk mendorong user info ke bawah -->
            <!-- Spacer -->
            <div style="flex-grow: 1;"></div>
            
            <!-- User info dan logout -->
            <div style="padding: 1rem; border-top: 1px solid #e9ecef; margin-top: 1rem;">
                <div style="display: flex; align-items: center;">
                    <div>
                        @if(auth()->user()->avatar)
                            <img src="{{ asset('img/users/' . auth()->user()->avatar) }}" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        @else
                            <i class="fas fa-user" style="width: 40px; height: 40px; font-size: 24px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center;"></i>
                        @endif
                    </div>
                    <div style="margin-left: 1rem;">
                        <div style="font-weight: 600; font-size: 0.875rem;">
                            {{ auth()->user()->firstname ?? auth()->user()->username }}
                        </div>
                        <div style="color: #6c757d; font-size: 0.75rem;">
                            {{ ucfirst(auth()->user()->role) }}
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="margin-top: 1rem;">
                    @csrf
                    <a href="{{ route('logout') }}" 
                       style="display: flex; align-items: center; text-decoration: none; padding: 0.5rem; border-radius: 0.375rem; transition: background-color 0.2s;"
                       onmouseover="this.style.backgroundColor='#f8f9fa'" 
                       onmouseout="this.style.backgroundColor='transparent'"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt" style="color: #dc3545; width: 1rem; text-align: center;"></i>
                        <span style="color: #dc3545; margin-left: 0.5rem;">Logout</span>
                    </a>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main content -->
    <main class="main-content position-relative border-radius-lg">
        <!-- Content -->
        <div class="container-fluid py-4">
            <!-- @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif -->

            @yield('content')
        </div>
    </main>
    @else
        @yield('content')
    @endauth

    <!-- Core JS Files -->
    <script src="https://demos.creative-tim.com/argon-dashboard/assets/js/core/popper.min.js"></script>
    <script src="https://demos.creative-tim.com/argon-dashboard/assets/js/core/bootstrap.min.js"></script>
    <script src="https://demos.creative-tim.com/argon-dashboard/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="https://demos.creative-tim.com/argon-dashboard/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="https://demos.creative-tim.com/argon-dashboard/assets/js/argon-dashboard.min.js"></script>

    <!-- Page specific scripts -->
    @stack('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force icon refresh
            const icons = document.querySelectorAll('.ni');
            icons.forEach(icon => {
                icon.style.display = 'none';
                setTimeout(() => icon.style.display = '', 10);
            });
        });
    </script>
    
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}"
        });
    </script>
    @endif
</body>
</html>
