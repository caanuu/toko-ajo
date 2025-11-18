<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Toko Ajo Inventory')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            /* --- KONFIGURASI WARNA & UKURAN --- */
            --sidebar-bg: #e6f7f5;       /* Hijau Mint Muda */
            --sidebar-width: 250px;      /* Lebar Normal */
            --sidebar-width-collapsed: 70px; /* Lebar Tertutup */

            --topbar-start: #2563eb;     /* Gradasi Biru Awal */
            --topbar-end: #4f46e5;       /* Gradasi Biru Akhir */

            --active-bg: #ffffff;        /* Background Menu Aktif */
            --active-text: #2563eb;      /* Warna Teks Menu Aktif */
            --body-bg: #f8f9fa;
        }

        body {
            background-color: var(--body-bg);
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
        }

        /* --- 1. TOP NAVBAR --- */
        .top-navbar {
            background: linear-gradient(90deg, var(--topbar-start) 0%, var(--topbar-end) 100%);
            height: 60px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.2rem;
            color: white !important;
            padding-left: 10px;
            letter-spacing: 0.5px;
        }

        /* --- 2. SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            position: fixed;
            top: 60px;
            bottom: 0;
            left: 0;
            overflow-y: auto;
            overflow-x: hidden; /* Sembunyikan scroll horizontal saat animasi */
            padding-top: 15px;
            padding-bottom: 50px;
            border-right: 1px solid #d1e7dd;
            z-index: 1020;
            transition: width 0.3s ease; /* Animasi Halus */
        }

        /* Font Menu */
        .sidebar .nav-link {
            color: #555;
            font-weight: 600;
            font-size: 0.9rem; /* Ukuran Font Menu */
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
            white-space: nowrap; /* Mencegah teks turun baris */
        }

        .sidebar .nav-link i {
            width: 25px;
            font-size: 1.1rem;
            margin-right: 10px;
            text-align: center;
            color: #6c757d;
            transition: margin 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.6);
            color: #000;
            padding-left: 25px;
        }

        /* Menu Aktif */
        .sidebar .nav-link.active {
            background-color: var(--active-bg);
            color: var(--active-text);
            box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
        }
        .sidebar .nav-link.active i { color: var(--active-text); }

        /* Section Header (MASTER, TRANSAKSI, dll) */
        .sidebar .nav-section {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        /* --- 3. MAIN CONTENT --- */
        .main-content {
            margin-top: 60px;
            margin-left: var(--sidebar-width);
            padding: 25px;
            min-height: calc(100vh - 60px);
            transition: margin-left 0.3s ease; /* Animasi Halus mengikuti sidebar */
        }

        /* --- 4. STATE: SIDEBAR COLLAPSED (TERTUTUP) --- */
        body.sb-collapsed .sidebar {
            width: var(--sidebar-width-collapsed);
        }

        body.sb-collapsed .main-content {
            margin-left: var(--sidebar-width-collapsed);
        }

        /* Sembunyikan Teks dan Section Header saat tertutup */
        body.sb-collapsed .sidebar .nav-link span,
        body.sb-collapsed .sidebar .nav-section {
            opacity: 0;
            pointer-events: none;
            display: none;
        }

        /* Pusatkan Ikon saat tertutup */
        body.sb-collapsed .sidebar .nav-link {
            justify-content: center;
            padding: 10px 0;
            margin-right: 5px;
        }

        body.sb-collapsed .sidebar .nav-link i {
            margin-right: 0;
            font-size: 1.2rem;
        }

        body.sb-collapsed .sidebar .nav-link:hover {
            padding-left: 0; /* Matikan efek geser saat tertutup */
            background-color: white;
        }

        /* Tombol Navbar Kanan */
        .nav-icon-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            width: 32px; height: 32px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 6px; margin-left: 8px;
            text-decoration: none; transition: 0.2s;
        }
        .nav-icon-btn:hover { background: rgba(255,255,255,0.4); color: white; }

        /* Tombol Toggle Burger */
        .btn-toggle {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.2rem;
            padding: 5px 10px;
            cursor: pointer;
        }

        /* Responsive Mobile */
        @media (max-width: 768px) {
            .sidebar { margin-left: calc(var(--sidebar-width) * -1); }
            .main-content { margin-left: 0; }

            /* Saat mobile active, munculkan sidebar */
            body.sb-mobile-active .sidebar { margin-left: 0; }

            /* Nonaktifkan logika collapsed desktop di mobile */
            body.sb-collapsed .sidebar { width: var(--sidebar-width); }
        }
    </style>
</head>
<body>

    <nav class="navbar top-navbar d-flex justify-content-between align-items-center px-3">
        <div class="d-flex align-items-center">
            <button class="btn-toggle me-3" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-store me-2"></i> Toko Ajo Asli Store
            </a>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown me-3">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                    <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center me-2 shadow-sm" style="width: 32px; height: 32px;">
                        <i class="fas fa-user small"></i>
                    </div>
                    <span class="d-none d-md-block fw-bold small">{{ Auth::check() ? Auth::user()->name : 'Administrator' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i class="fas fa-cog me-2 text-muted"></i> Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

            <a href="#" class="nav-icon-btn" title="Mode Gelap"><i class="fas fa-moon"></i></a>
        </div>
    </nav>

    @include('layouts.sidebar')

    <div class="main-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')

        <footer class="mt-5 text-center text-muted small py-3">
            &copy; 2025 Toko Ajo Asli Store. All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const body = document.body;

            // 1. Cek LocalStorage (Agar posisi tersimpan saat refresh)
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                body.classList.add('sb-collapsed');
            }

            // 2. Event Listener Klik
            sidebarToggle.addEventListener('click', function (e) {
                e.preventDefault();

                if (window.innerWidth > 768) {
                    // Mode Desktop: Kecilkan/Besarkan
                    body.classList.toggle('sb-collapsed');
                    // Simpan state ke storage
                    localStorage.setItem('sidebar-collapsed', body.classList.contains('sb-collapsed'));
                } else {
                    // Mode Mobile: Tampilkan/Sembunyikan
                    body.classList.toggle('sb-mobile-active');
                }
            });
        });
    </script>
</body>
</html>
