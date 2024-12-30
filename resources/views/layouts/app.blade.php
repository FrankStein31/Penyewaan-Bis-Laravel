<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

    <!-- Argon CSS & Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/argon-design-system-free@1.2.0/assets/css/argon-design-system.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/argon-dashboard@1.2.0/assets/css/argon-dashboard.min.css">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/nucleo-icons@1.7.0/css/nucleo-icons.css" rel="stylesheet">

    
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        /* Sidebar */
        .sidebar {
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .sidebar .brand {
            padding: 1rem;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #eee;
        }
        
        .sidebar .nav-link {
            padding: 0.8rem 1rem;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: #f8f9fa;
            color: #0d6efd;
        }
        
        .sidebar .nav-link i {
            width: 20px;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 200px;
            padding: 20px;
        }
        
        /* Dashboard Cards */
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
        
        /* Table */
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
    </style>
</head>

<body>
    @auth
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="brand">
            PO Bis Ekasari
        </div>
        <nav class="mt-3">
            <!-- Owner Sidebar -->
            @if(auth()->user()->role == 'owner')
                <a class="nav-link {{ Request::is('owner/dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                    <i class="fas fa-tv"></i> Dashboard
                </a>
                <a class="nav-link {{ Request::is('owner/statistics/daily') ? 'active' : '' }}" href="{{ route('owner.statistics.daily') }}">
                    <i class="fas fa-chart-line"></i> Statistik Harian
                </a>
                <a class="nav-link {{ Request::is('owner/statistics/monthly') ? 'active' : '' }}" href="{{ route('owner.statistics.monthly') }}">
                    <i class="fas fa-chart-bar"></i> Statistik Bulanan
                </a>
                <a class="nav-link {{ Request::is('owner/statistics/yearly') ? 'active' : '' }}" href="{{ route('owner.statistics.yearly') }}">
                    <i class="fas fa-chart-pie"></i> Statistik Tahunan
                </a>
                <a class="nav-link {{ Request::is('owner/statistics/bus') ? 'active' : '' }}" href="{{ route('owner.statistics.bus') }}">
                    <i class="fas fa-bus"></i> Statistik Penggunaan Bis
                </a>
                <a class="nav-link {{ Request::is('owner/statistics/driver') ? 'active' : '' }}" href="{{ route('owner.statistics.driver') }}">
                    <i class="fas fa-user-tie"></i> Jam Terbang Supir
                </a>
                <a class="nav-link {{ Request::is('owner/statistics/fleet') ? 'active' : '' }}" href="{{ route('owner.statistics.fleet') }}">
                    <i class="fas fa-star"></i> Ranking Armada
                </a>
                <a class="nav-link {{ Request::is('owner/users*') ? 'active' : '' }}" href="{{ route('owner.users.index') }}">
                    <i class="fas fa-users-cog"></i> Manajemen User
                </a>

            <!-- Admin Sidebar -->
            @elseif(auth()->user()->role == 'admin')
                <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tv"></i> Dashboard
                </a>
                <a class="nav-link {{ Request::is('admin/drivers*') ? 'active' : '' }}" href="{{ route('admin.drivers.index') }}">
                    <i class="fas fa-user-tie"></i> Data Supir
                </a>
                <a class="nav-link {{ Request::is('admin/conductors*') ? 'active' : '' }}" href="{{ route('admin.conductors.index') }}">
                    <i class="fas fa-user"></i> Data Kernet
                </a>
                <a class="nav-link {{ Request::is('admin/buses*') ? 'active' : '' }}" href="{{ route('admin.buses.index') }}">
                    <i class="fas fa-bus"></i> Data Bis
                </a>
                <a class="nav-link {{ Request::is('admin/customers*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                    <i class="fas fa-users"></i> Data Pelanggan
                </a>
                <a class="nav-link {{ Request::is('admin/rentals*') ? 'active' : '' }}" href="{{ route('admin.rentals.index') }}">
                    <i class="fas fa-ticket-alt"></i> Transaksi Penyewaan
                </a>
                <a class="nav-link {{ Request::is('admin/bus-status') ? 'active' : '' }}" href="{{ route('admin.buses.status') }}">
                    <i class="fas fa-clock"></i> Status Bis
                </a>
                <a class="nav-link {{ Request::is('admin/payments*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                    <i class="fas fa-money-bill"></i> Pembayaran
                </a>
                <a class="nav-link {{ Request::is('admin/requests*') ? 'active' : '' }}" href="{{ route('admin.requests.index') }}">
                    <i class="fas fa-bell"></i> Pengajuan
                </a>

            <!-- Customer Sidebar -->
            @else
                <a class="nav-link {{ Request::is('customer/dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                    <i class="fas fa-tv"></i> Dashboard
                </a>
                <a class="nav-link {{ Request::is('customer/profile*') ? 'active' : '' }}" href="{{ route('customer.profile.edit') }}">
                    <i class="fas fa-user"></i> Profile
                </a>
                <a class="nav-link {{ Request::is('customer/search*') ? 'active' : '' }}" href="{{ route('customer.search') }}">
                    <i class="fas fa-search"></i> Cari & Pesan
                </a>
                <a class="nav-link {{ Request::is('customer/rentals*') ? 'active' : '' }}" href="{{ route('customer.rentals') }}">
                    <i class="fas fa-history"></i> Riwayat Sewa
                </a>
                <a class="nav-link {{ Request::is('customer/payments*') ? 'active' : '' }}" href="{{ route('customer.payments') }}">
                    <i class="fas fa-money-bill"></i> Pembayaran
                </a>
                <a class="nav-link {{ Request::is('customer/ratings*') ? 'active' : '' }}" href="{{ route('customer.ratings') }}">
                    <i class="fas fa-star"></i> Beri Rating
                </a>
            @endif

            <a class="nav-link text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @yield('content')
    </div>
    @else
        @yield('content')
    @endauth
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    
    <!-- Argon JS -->
    <script src="https://cdn.jsdelivr.net/npm/argon-design-system-free@1.2.0/assets/js/argon-design-system.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/argon-dashboard@1.2.0/assets/js/argon-dashboard.min.js"></script>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
