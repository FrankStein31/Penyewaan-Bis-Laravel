<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PO Bis Ekasari - Sewa Bis Terpercaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .hero {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), 
                        url('/images/hero-bg.jpg') center/cover;
            height: 100vh;
            color: white;
            display: flex;
            align-items: center;
            text-align: center;
        }

        .feature-card {
            border: none;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .bus-card img {
            height: 200px;
            object-fit: cover;
        }

        .testimonial-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }

        .footer {
            background: #333;
            color: white;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">PO Bis Ekasari</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fleet">Armada</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimonials">Testimonial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">Daftar</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ url('/dashboard') }}">Dashboard</a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="display-4 mb-4">Sewa Bis Mudah dan Terpercaya</h1>
            <p class="lead mb-4">Layanan sewa bis untuk berbagai kebutuhan perjalanan Anda</p>
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Mulai Sekarang</a>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Mengapa Memilih Kami?</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-bus fa-3x mb-3 text-primary"></i>
                            <h5>Armada Berkualitas</h5>
                            <p>Bis modern dan terawat untuk kenyamanan perjalanan Anda</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-user-tie fa-3x mb-3 text-primary"></i>
                            <h5>Supir Profesional</h5>
                            <p>Didukung oleh supir berpengalaman dan profesional</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card text-center">
                        <div class="card-body">
                            <i class="fas fa-headset fa-3x mb-3 text-primary"></i>
                            <h5>Layanan 24/7</h5>
                            <p>Dukungan pelanggan siap membantu kapanpun</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fleet -->
    <section id="fleet" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Armada Kami</h2>
            <div class="row g-4">
                @foreach($buses as $bus)
                <div class="col-md-4">
                    <div class="card bus-card">
                        <img src="{{ $bus->image_url }}" class="card-img-top" alt="{{ $bus->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $bus->name }}</h5>
                            <p class="card-text">
                                <i class="fas fa-users"></i> {{ $bus->capacity }} Kursi<br>
                                <i class="fas fa-star text-warning"></i> 
                                {{ number_format($bus->ratings_avg_rating, 1) }}
                            </p>
                            <a href="{{ route('customer.search') }}" class="btn btn-primary">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Apa Kata Mereka?</h2>
            <div class="row g-4">
                @foreach($testimonials as $testimonial)
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $testimonial->user->avatar }}" class="rounded-circle me-3" width="50">
                            <div>
                                <h5 class="mb-0">{{ $testimonial->user->name }}</h5>
                                <small class="text-muted">{{ $testimonial->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <p class="mb-0">{{ $testimonial->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Hubungi Kami</h2>
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5>Informasi Kontak</h5>
                    <p>
                        <i class="fas fa-map-marker-alt"></i> Jl. Raya No. 123, Denpasar, Bali<br>
                        <i class="fas fa-phone"></i> (0361) 123456<br>
                        <i class="fas fa-envelope"></i> info@ekasari.com
                    </p>
                    <h5 class="mt-4">Jam Operasional</h5>
                    <p>
                        Senin - Minggu: 08:00 - 21:00 WITA
                    </p>
                </div>
                <div class="col-md-6">
                    <form>
                        <div class="mb-3">
                            <input type="text" class="form-control" placeholder="Nama">
                        </div>
                        <div class="mb-3">
                            <input type="email" class="form-control" placeholder="Email">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control" rows="4" placeholder="Pesan"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>PO Bis Ekasari</h5>
                    <p>Melayani penyewaan bis untuk berbagai kebutuhan perjalanan Anda dengan armada berkualitas dan pelayanan terbaik.</p>
                </div>
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="#features" class="text-white">Layanan</a></li>
                        <li><a href="#fleet" class="text-white">Armada</a></li>
                        <li><a href="#testimonials" class="text-white">Testimonial</a></li>
                        <li><a href="#contact" class="text-white">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Ikuti Kami</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-whatsapp fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4 bg-light">
            <div class="text-center">
                <small>&copy; 2024 PO Bis Ekasari. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 