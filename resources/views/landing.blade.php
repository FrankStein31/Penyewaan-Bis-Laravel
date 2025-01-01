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

        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.95) 0%, rgba(30, 64, 175, 0.95) 100%),
                        url('/api/placeholder/1920/1080') center/cover;
            padding-top: 8rem;
            display: flex;
            align-items: center;
        }

        .hero h1 {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.2;
            background: linear-gradient(to right, #fff, #e2e8f0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
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

        .testimonial-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            height: 100%;
        }

        .testimonial-card .quote {
            font-size: 4rem;
            color: var(--primary);
            line-height: 1;
            margin-bottom: 1rem;
        }

        .contact-form .form-control {
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .contact-form .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
            padding: 5rem 0 2rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
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
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="#testimonials">Testimonial</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-medium" href="#contact">Kontak</a>
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
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="animate__animated animate__fadeInUp">Perjalanan Nyaman Bersama Kami</h1>
                    <p class="lead text-white-50 mb-5 animate__animated animate__fadeInUp animate__delay-1s">
                        Nikmati perjalanan dengan armada bus modern, supir profesional, dan layanan 24/7 untuk kebutuhan transportasi Anda
                    </p>
                    <div class="animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="#fleet" class="btn btn-light btn-lg me-3">
                            Lihat Armada
                        </a>
                        <a href="#contact" class="btn btn-outline-light btn-lg">
                            Hubungi Kami
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <img src="/api/placeholder/600/400" alt="Bus" class="img-fluid rounded-3 animate__animated animate__fadeInRight animate__delay-1s">
                </div>
            </div>
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
                            <a href="#contact" class="btn btn-primary w-100">Pesan Sekarang</a>
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
                            <a href="#contact" class="btn btn-primary w-100">Pesan Sekarang</a>
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
                            <a href="#contact" class="btn btn-primary w-100">Pesan Sekarang</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5 bg-light" id="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-heading">Apa Kata Mereka?</h2>
                <p class="section-subheading">Testimonial dari pelanggan setia kami</p>
            </div>
            <div class <div class="row g-4">
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="quote">"</div>
                        <p class="mb-4">Pelayanan sangat memuaskan, armada bus modern dan nyaman. Supir sangat profesional dan berpengalaman. Recommended!</p>
                        <div class="d-flex align-items-center">
                            <img src="/api/placeholder/48/48" alt="User" class="rounded-circle">
                            <div class="ms-3">
                                <h5 class="mb-0">Budi Santoso</h5>
                                <small class="text-muted">Pengusaha</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="quote">"</div>
                        <p class="mb-4">Sudah berkali-kali sewa bus di Ekasari untuk study tour sekolah. Pelayanan konsisten bagus dan harga bersaing.</p>
                        <div class="d-flex align-items-center">
                            <img src="/api/placeholder/48/48" alt="User" class="rounded-circle">
                            <div class="ms-3">
                                <h5 class="mb-0">Dewi Putri</h5>
                                <small class="text-muted">Guru</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card">
                        <div class="quote">"</div>
                        <p class="mb-4">Bus selalu dalam kondisi prima, AC dingin, dan staff yang ramah. Pasti akan menggunakan jasa Ekasari lagi!</p>
                        <div class="d-flex align-items-center">
                            <img src="/api/placeholder/48/48" alt="User" class="rounded-circle">
                            <div class="ms-3">
                                <h5 class="mb-0">Made Wirawan</h5>
                                <small class="text-muted">Tour Guide</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="py-5" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h2 class="section-heading">Hubungi Kami</h2>
                    <p class="section-subheading">Ada pertanyaan? Kami siap membantu Anda</p>
                    
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-shape" style="width: 48px; height: 48px;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Alamat</h5>
                            <p class="text-muted mb-0">Jl. Raya No. 123, Denpasar, Bali</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-shape" style="width: 48px; height: 48px; background: #10b981;">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Telepon</h5>
                            <p class="text-muted mb-0">(0361) 123456</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        <div class="icon-shape" style="width: 48px; height: 48px; background: #6366f1;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1">Email</h5>
                            <p class="text-muted mb-0">info@ekasari.com</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <form class="contact-form">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" placeholder="Masukkan nama lengkap">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" placeholder="nama@email.com">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Subjek</label>
                                        <input type="text" class="form-control" placeholder="Subjek pesan">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Pesan</label>
                                        <textarea class="form-control" rows="5" placeholder="Tulis pesan Anda disini..."></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4 g-lg-5 mb-5">
                <div class="col-lg-4">
                    <h4 class="mb-4">PO Bis Ekasari</h4>
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
                <div class="col-lg-2">
                    <h5 class="mb-4">Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="#features" class="text-muted text-decoration-none">Layanan</a></li>
                        <li class="mb-3"><a href="#fleet" class="text-muted text-decoration-none">Armada</a></li>
                        <li class="mb-3"><a href="#testimonials" class="text-muted text-decoration-none">Testimonial</a></li>
                        <li><a href="#contact" class="text-muted text-decoration-none">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="mb-4">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Sewa Bus Pariwisata</a></li>
                        <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Antar Jemput Karyawan</a></li>
                        <li class="mb-3"><a href="#" class="text-muted text-decoration-none">Study Tour</a></li>
                        <li><a href="#" class="text-muted text-decoration-none">City Tour</a></li>
                    </ul>
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
            <div class="row py-4">
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