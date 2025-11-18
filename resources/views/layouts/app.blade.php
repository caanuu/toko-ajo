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
            /* --- LIGHT MODE (Default) --- */
            --sidebar-bg: #e6f7f5;
            --sidebar-border: #d1e7dd;
            --text-main: #333333;
            --text-muted: #6c757d;
            --text-menu: #444444;
            --menu-hover: rgba(0, 0, 0, 0.05);
            --body-bg: #f8f9fa;
            --card-bg: #ffffff;
            --card-border: rgba(0, 0, 0, 0.125);
            --input-bg: #ffffff;
            --input-border: #ced4da;
            --table-head-bg: #f8f9fa;

            /* Ukuran */
            --sidebar-width: 250px;
            --sidebar-width-collapsed: 70px;
            --topbar-height: 60px;
        }

        /* --- DARK MODE (High Contrast) --- */
        [data-theme="dark"] {
            --sidebar-bg: #151521;
            /* Lebih gelap agar elegan */
            --sidebar-border: #2b2b40;
            --text-main: #ffffff;
            /* Teks Utama Putih Mutlak */
            --text-muted: #b0b3b8;
            /* Abu terang */
            --text-menu: #e0e0e0;
            /* Menu Putih Terang */
            --menu-hover: rgba(255, 255, 255, 0.1);
            --body-bg: #0f0f13;
            /* Background Body Sangat Gelap */
            --card-bg: #1e1e2d;
            /* Card sedikit lebih terang dari body */
            --card-border: #2b2b40;
            --input-bg: #151521;
            /* Input gelap */
            --input-border: #2b2b40;
            --table-head-bg: #2b2b40;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-main);
            font-family: 'Nunito', sans-serif;
            overflow-x: hidden;
            transition: background-color 0.3s, color 0.3s;
        }

        /* --- 1. TOP NAVBAR --- */
        .top-navbar {
            background: linear-gradient(90deg, #2563eb 0%, #4f46e5 100%);
            height: var(--topbar-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.2rem;
            color: white !important;
            padding-left: 15px;
        }

        /* --- 2. SIDEBAR --- */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            position: fixed;
            top: var(--topbar-height);
            bottom: 0;
            left: 0;
            border-right: 1px solid var(--sidebar-border);
            z-index: 1020;
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header-wrapper {
            padding: 10px 15px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            justify-content: flex-end;
            align-items: center;
            min-height: 50px;
        }

        .sidebar-menu-wrapper {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding-bottom: 20px;
        }

        /* Scrollbar Halus */
        .sidebar-menu-wrapper::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu-wrapper::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-menu-wrapper::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 10px;
        }

        .sidebar-toggle-btn {
            cursor: pointer;
            color: var(--text-muted);
            font-size: 1.2rem;
            transition: 0.2s;
        }

        .sidebar-toggle-btn:hover {
            color: #2563eb;
        }

        [data-theme="dark"] .sidebar-toggle-btn:hover {
            color: #fff;
        }

        /* MENU LINKS - FIX WARNA */
        .sidebar .nav-link {
            color: var(--text-menu);
            font-weight: 600;
            font-size: 0.9rem;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
            border-radius: 0 25px 25px 0;
            margin-right: 15px;
            white-space: nowrap;
        }

        .sidebar .nav-link i {
            width: 25px;
            font-size: 1.1rem;
            margin-right: 10px;
            text-align: center;
            color: var(--text-muted);
        }

        .sidebar .nav-link:hover {
            background-color: var(--menu-hover);
            padding-left: 25px;
            color: var(--text-main);
        }

        /* Menu Aktif */
        .sidebar .nav-link.active {
            background-color: var(--card-bg);
            color: #2563eb !important;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] .sidebar .nav-link.active {
            background-color: #2563eb;
            /* Biru Solid di Dark Mode agar jelas */
            color: white !important;
        }

        .sidebar .nav-link.active i {
            color: inherit !important;
        }

        /* SECTION HEADERS - WARNA NEON UTK DARK MODE */
        .sidebar .nav-section {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 20px 20px 5px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        /* Override Warna Bootstrap di Sidebar */
        [data-theme="dark"] .text-primary {
            color: #60a5fa !important;
        }

        /* Biru Muda */
        [data-theme="dark"] .text-success {
            color: #4ade80 !important;
        }

        /* Hijau Neon */
        [data-theme="dark"] .text-warning {
            color: #facc15 !important;
        }

        /* Kuning Terang */
        [data-theme="dark"] .text-purple {
            color: #c084fc !important;
        }

        /* Ungu Terang */
        [data-theme="dark"] .text-secondary {
            color: #9ca3af !important;
        }

        /* Abu Terang */

        /* --- 3. MAIN CONTENT --- */
        .main-content {
            margin-top: var(--topbar-height);
            margin-left: var(--sidebar-width);
            padding: 25px;
            min-height: calc(100vh - var(--topbar-height));
            transition: margin-left 0.3s ease;
        }

        /* --- 4. COLLAPSED STATE --- */
        body.sb-collapsed .sidebar {
            width: var(--sidebar-width-collapsed);
        }

        body.sb-collapsed .main-content {
            margin-left: var(--sidebar-width-collapsed);
        }

        body.sb-collapsed .sidebar .nav-link span,
        body.sb-collapsed .sidebar .nav-section {
            opacity: 0;
            display: none;
        }

        body.sb-collapsed .sidebar-header-wrapper {
            justify-content: center;
            padding: 10px 0;
        }

        body.sb-collapsed .sidebar .nav-link {
            justify-content: center;
            padding: 10px 0;
            margin-right: 5px;
        }

        body.sb-collapsed .sidebar .nav-link i {
            margin-right: 0;
            font-size: 1.3rem;
        }

        body.sb-collapsed .sidebar .nav-link:hover {
            padding-left: 0;
        }

        /* --- 5. GLOBAL COMPONENTS (FIX DARK MODE) --- */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--card-border);
            color: var(--text-main);
        }

        .card-header {
            background-color: rgba(0, 0, 0, 0.02);
            border-bottom: 1px solid var(--card-border);
            color: var(--text-main);
        }

        /* Tabel */
        .table {
            color: var(--text-main);
            --bs-table-bg: transparent;
            border-color: var(--card-border);
        }

        .table-light tr th {
            background-color: var(--table-head-bg) !important;
            color: var(--text-main);
            border-color: var(--card-border);
        }

        .table-hover tbody tr:hover {
            color: var(--text-main);
            background-color: var(--menu-hover);
        }

        /* Form Input (Penting untuk Laporan) */
        .form-control,
        .form-select {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            color: var(--text-main);
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--input-bg);
            color: var(--text-main);
            border-color: #2563eb;
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.25);
        }

        /* Placeholder color fix */
        [data-theme="dark"] ::placeholder {
            color: #6c757d;
            opacity: 1;
        }

        [data-theme="dark"] option {
            background-color: #1e1e2d;
        }

        /* Fix dropdown option bg */

        .nav-icon-btn {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin-left: 8px;
            cursor: pointer;
            color: white;
            background: rgba(255, 255, 255, 0.15);
            transition: 0.2s;
        }

        .nav-icon-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: calc(var(--sidebar-width) * -1);
            }

            .main-content {
                margin-left: 0;
            }

            body.sb-mobile-active .sidebar {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block !important;
                margin-right: 15px;
                color: white;
                background: none;
                border: none;
                font-size: 1.5rem;
            }

            .sidebar-header-wrapper {
                display: none;
            }
        }

        .mobile-toggle {
            display: none;
        }
    </style>
</head>

<body>

    <nav class="navbar top-navbar px-3">
        <div class="d-flex align-items-center">
            <button class="mobile-toggle" id="mobileToggle"><i class="fas fa-bars"></i></button>
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                </i> Toko Ajo Asli Store
            </a>
        </div>

        <div class="d-flex align-items-center">
            <div class="dropdown me-2">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                    id="userDropdown" data-bs-toggle="dropdown">
                    <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center me-2 shadow-sm"
                        style="width: 32px; height: 32px;">
                        <i class="fas fa-user small"></i>
                    </div>
                    <span
                        class="d-none d-md-block fw-bold small">{{ Auth::check() ? Auth::user()->name : 'Administrator' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li><a class="dropdown-item" href="{{ route('settings.index') }}"><i
                                class="fas fa-cog me-2 text-muted"></i> Pengaturan</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i
                                    class="fas fa-sign-out-alt me-2"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="nav-icon-btn" id="themeToggle" title="Mode Gelap/Terang">
                <i class="fas fa-moon"></i>
            </div>
        </div>
    </nav>

    @include('layouts.sidebar')

    <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
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
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileToggle = document.getElementById('mobileToggle');
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = themeToggle.querySelector('i');

            // 1. SIDEBAR LOGIC
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed && window.innerWidth > 768) {
                body.classList.add('sb-collapsed');
                if (sidebarToggle) sidebarToggle.querySelector('i').classList.replace('fa-outdent', 'fa-indent');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    body.classList.toggle('sb-collapsed');
                    localStorage.setItem('sidebar-collapsed', body.classList.contains('sb-collapsed'));

                    const icon = this.querySelector('i');
                    if (body.classList.contains('sb-collapsed')) {
                        icon.classList.replace('fa-outdent', 'fa-indent');
                    } else {
                        icon.classList.replace('fa-indent', 'fa-outdent');
                    }
                });
            }

            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    body.classList.toggle('sb-mobile-active');
                });
            }

            // 2. DARK MODE LOGIC
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                body.setAttribute('data-theme', 'dark');
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            }

            themeToggle.addEventListener('click', function() {
                if (body.getAttribute('data-theme') === 'dark') {
                    body.removeAttribute('data-theme');
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.replace('fa-sun', 'fa-moon');
                } else {
                    body.setAttribute('data-theme', 'dark');
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.replace('fa-moon', 'fa-sun');
                }
            });
        });
    </script>
</body>

</html>
