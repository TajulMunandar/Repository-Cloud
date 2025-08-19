<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Dokumen Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .hero {
            min-height: 100vh;
            background: linear-gradient(to right, #4e54c8, #8f94fb);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
        }

        .features i {
            font-size: 2.5rem;
            color: #4e54c8;
        }

        .stats {
            background: #f8f9fa;
            padding: 60px 0;
        }

        .stat-box {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            transition: transform .3s ease;
        }

        .stat-box:hover {
            transform: translateY(-5px);
        }

        footer {
            background: #212529;
            color: #bbb;
            padding: 20px 0;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Informatics Cloud</a>
            <div class="ms-auto">
                <a href="/login" class="btn btn-primary px-4">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <h1 class="mb-4">Kelola Dokumen Anda dengan Mudah & Aman</h1>
            <p class="lead mb-4">Simpan, bagikan, dan kelola file secara modern dalam satu platform terpercaya.</p>
            <a href="#features" class="btn btn-light btn-lg px-4 me-2">Pelajari Lebih</a>
            <a href="/register" class="btn btn-outline-light btn-lg px-4">Daftar Gratis</a>
        </div>
    </section>

    <!-- Features -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Fitur Unggulan</h2>
                <p class="text-muted">Semua yang Anda butuhkan untuk mengelola file</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <i class="fa-solid fa-cloud-upload-alt mb-3"></i>
                    <h5 class="fw-semibold">Upload Mudah</h5>
                    <p class="text-muted">Unggah file dalam berbagai format dengan cepat & aman.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fa-solid fa-lock mb-3"></i>
                    <h5 class="fw-semibold">Keamanan Data</h5>
                    <p class="text-muted">Setiap file dilindungi dengan enkripsi dan akses terkontrol.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fa-solid fa-chart-line mb-3"></i>
                    <h5 class="fw-semibold">Statistik Real-Time</h5>
                    <p class="text-muted">Pantau aktivitas unduhan & view dokumen secara langsung.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="fw-bold">1,200+</h2>
                        <p class="mb-0">Pengguna Aktif</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="fw-bold">5,000+</h2>
                        <p class="mb-0">File Tersimpan</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="fw-bold">20K+</h2>
                        <p class="mb-0">Aktivitas</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-box">
                        <h2 class="fw-bold">99.9%</h2>
                        <p class="mb-0">Uptime Server</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="mb-0">© 2025 Informatics Cloud. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
