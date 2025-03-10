<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PO Bis Ekasari - Layanan sewa bus terpercaya dengan armada berkualitas dan pelayanan terbaik">
    <title>PO Bis Ekasari - Sewa Bis Terpercaya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --secondary: #f8fafc;
            --dark: #0f172a;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark);
            overflow-x: hidden;
        }

        .navbar {
            padding: 1rem 0;
            transition: all 0.3s ease;
            background: transparent;
        }

        .navbar.scrolled {
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                        url('https://lidyatrans.web.id/wp-content/uploads/2018/06/Sewa-Bus-Pariwisata-PO-Ekasari-Kediri-Murah.jpg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-content {
            color: white;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-content p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        .btn-hero {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            background: var(--primary);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-hero:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .navbar {
            background: transparent !important;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-light .navbar-nav .nav-link {
            color: white;
        }

        .navbar.scrolled .navbar-nav .nav-link {
            color: var(--dark);
        }

        .feature-card {
            border: 0;
            border-radius: 1rem;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            background: #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .icon-shape {
            width: 60px;
            height: 60px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            background: var(--primary);
            color: white;
        }

        .bus-card {
            border: 0;
            border-radius: 1rem;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .bus-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .bus-card img {
            height: 240px;
            object-fit: cover;
            transition: all 0.5s ease;
        }

        .bus-card:hover img {
            transform: scale(1.05);
        }

        .btn-primary {
            background: var(--primary);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .section-heading {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .section-subheading {
            color: #64748b;
            font-size: 1.125rem;
            margin-bottom: 3rem;
        }

        footer {
            background: var(--secondary);
            padding: 3rem 0 2rem;
        }

        .service-icon {
            font-size: 1.5rem;
            margin-right: 0.75rem;
            color: var(--primary);
        }

        .service-item {
            transition: all 0.3s ease;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .service-item:hover {
            background: rgba(37, 99, 235, 0.05);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .hero-content h1 {
                font-size: 2.5rem;
            }
            
            .section-heading {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-light">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-bus me-2" style="font-size: 28px; color: var(--primary)"></i>
                <span class="fw-bold text-primary">PO Bis Ekasari</span>
            </a>
            <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="#features">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="#fleet">Armada</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="btn btn-outline-primary px-4 fw-semibold" href="/login">
                            <i class="fas fa-sign-in-alt me-2"></i>Masuk
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary px-4 fw-semibold" href="/register">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero-section">
        <div class="hero-content animate__animated animate__fadeIn">
            <h1>Perjalanan Nyaman Bersama Kami</h1>
            <p>Nikmati perjalanan dengan armada bus berkualitas dan pelayanan terbaik</p>
            <a href="#fleet" class="btn btn-hero">Pilih Armada</a>
        </div>
    </section>

    <!-- Features -->
    <section class="py-5 mt-n5" id="features">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="feature-card">
                        <div class="icon-shape">
                            <i class="fas fa-bus fa-lg"></i>
                        </div>
                        <h4>Armada Modern</h4>
                        <p class="text-muted mb-0">Bus berkualitas tinggi dengan perawatan rutin untuk kenyamanan optimal</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card">
                        <div class="icon-shape" style="background: #10b981;">
                            <i class="fas fa-shield-alt fa-lg"></i>
                        </div>
                        <h4>Keamanan Terjamin</h4>
                        <p class="text-muted mb-0">Dilengkapi fitur keselamatan modern dan asuransi perjalanan lengkap</p>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="feature-card">
                        <div class="icon-shape" style="background: #6366f1;">
                            <i class="fas fa-headset fa-lg"></i>
                        </div>
                        <h4>Layanan 24/7</h4>
                        <p class="text-muted mb-0">Tim support siap membantu Anda kapanpun dan dimanapun</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fleet -->
    <section class="py-5" id="fleet">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading">Armada Kami</h2>
                <p class="section-subheading">Pilihan bus premium untuk setiap kebutuhan perjalanan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="bus-card">
                        <img src="/api/placeholder/400/300" alt="Big Bus" class="img-fluid">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">Big Bus</h4>
                                <span class="badge bg-primary">59 Seats</span>
                            </div>
                            <p class="text-muted mb-4">Luxury bus dengan fasilitas lengkap untuk perjalanan jarak jauh</p>
                            <a href="/register" class="btn btn-primary w-100">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bus-card">
                        <img src="/api/placeholder/400/300" alt="Medium Bus" class="img-fluid">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">Medium Bus</h4>
                                <span class="badge bg-primary">35 Seats</span>
                            </div>
                            <p class="text-muted mb-4">Bus nyaman untuk grup sedang dan perjalanan wisata</p>
                            <a href="/register" class="btn btn-primary w-100">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bus-card">
                        <img src="/api/placeholder/400/300" alt="Mini Bus" class="img-fluid">
                        <div class="p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">Mini Bus</h4>
                                <span class="badge bg-primary">17 Seats</span>
                            </div>
                            <p class="text-muted mb-4">Ideal untuk grup kecil dan perjalanan keluarga</p>
                            <a href="/register" class="btn btn-primary w-100">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading">Layanan Kami</h2>
                <p class="section-subheading">Solusi transportasi untuk berbagai kebutuhan Anda</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="service-item d-flex align-items-start">
                        <i class="fas fa-mountain service-icon"></i>
                        <div>
                            <h4>Wisata Domestik</h4>
                            <p class="text-muted mb-0">Nikmati perjalanan wisata di dalam negeri dengan armada bus yang nyaman dan aman</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-item d-flex align-items-start">
                        <i class="fas fa-briefcase service-icon"></i>
                        <div>
                            <h4>Transportasi Karyawan</h4>
                            <p class="text-muted mb-0">Solusi transportasi untuk antar jemput karyawan perusahaan Anda</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-item d-flex align-items-start">
                        <i class="fas fa-graduation-cap service-icon"></i>
                        <div>
                            <h4>Study Tour</h4>
                            <p class="text-muted mb-0">Layanan khusus untuk kegiatan study tour sekolah dengan pengalaman terbaik</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="service-item d-flex align-items-start">
                        <i class="fas fa-users service-icon"></i>
                        <div>
                            <h4>Acara Keluarga</h4>
                            <p class="text-muted mb-0">Transportasi nyaman untuk reunian keluarga, pernikahan, dan acara spesial lainnya</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4 g-lg-5 mb-4">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-bus me-2" style="font-size: 28px; color: var(--primary)"></i>
                        <h4 class="mb-0">PO Bis Ekasari</h4>
                    </div>
                    <p class="text-muted">Melayani penyewaan bis untuk berbagai kebutuhan perjalanan Anda dengan armada berkualitas dan pelayanan terbaik.</p>
                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="btn btn-light rounded-circle">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-light rounded-circle">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-light rounded-circle">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h5 class="mb-4">Layanan</h5>
                    <div class="row">
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Sewa Bus Pariwisata</a></li>
                                <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Antar Jemput Karyawan</a></li>
                            </ul>
                        </div>
                        <div class="col-6">
                            <ul class="list-unstyled">
                                <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Study Tour</a></li>
                                <li><a href="#" class="text-muted text-decoration-none">City Tour</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <h5 class="mb-4">Kontak</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3 text-muted"><i class="fas fa-phone me-2"></i>(0361) 123456</li>
                        <li class="mb-3 text-muted"><i class="fas fa-envelope me-2"></i>info@ekasari.com</li>
                        <li class="text-muted"><i class="fas fa-map-marker-alt me-2"></i>Jl. Raya No. 123, Denpasar, Bali</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row py-3">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0 text-muted">&copy; 2024 PO Bis Ekasari. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="list-inline mb-0">
                        <li class="list-inline-item"><a href="#" class="text-muted text-decoration-none">Terms</a></li>
                        <li class="list-inline-item ms-3"><a href="#" class="text-muted text-decoration-none">Privacy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>