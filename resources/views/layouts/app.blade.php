<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Happy Cake - Tiệm bánh đặt trước')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Pacifico&display=swap" rel="stylesheet">

    <style>
        :root {
            --accent: #f4a261;            /* cam nhạt / vàng bơ */
            --accent-hover: #e08c4a;
            --accent-soft: #ffe8d6;
            --warm-bg: #fffaf3;            /* nền kem ấm */
            --warm-header: #ffd8a8;
            --warm-card: #ffefd5;
            --text-dark: #5b3a1e;          /* chữ nâu caramel */
        }

        body {
            font-family: 'Quicksand', sans-serif;
            background-color: var(--warm-bg);
            color: var(--text-dark);
        }

        /* Top Bar */
        .top-bar {
            background-color: var(--text-dark);
            color: #fff;
            font-size: 0.85rem;
            padding: 6px 0;
        }
        .top-bar a {
            color: #fff;
            text-decoration: none;
        }
        .top-bar a:hover {
            color: var(--accent);
        }

        /* Navbar */
        .navbar-pcshop {
            background-color: #fff !important;
            box-shadow: 0 2px 15px rgba(244, 162, 97, 0.15);
            padding: 0.6rem 0;
            border-bottom: 3px solid var(--accent);
        }
        .navbar-pcshop .navbar-brand {
            font-family: 'Pacifico', cursive;
            font-weight: 400;
            font-size: 1.8rem;
            color: var(--accent) !important;
            letter-spacing: 1px;
        }
        .navbar-pcshop .navbar-brand i {
            margin-right: 6px;
        }
        .navbar-pcshop .nav-link {
            color: var(--text-dark) !important;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            transition: color 0.2s;
        }
        .navbar-pcshop .nav-link:hover,
        .navbar-pcshop .nav-link.active {
            color: var(--accent) !important;
        }
        .navbar-pcshop .dropdown-menu {
            background-color: #fff;
            border: 1px solid var(--accent-soft);
        }
        .navbar-pcshop .dropdown-item {
            color: var(--text-dark);
        }
        .navbar-pcshop .dropdown-item:hover {
            background-color: var(--accent-soft);
            color: var(--accent-hover);
        }

        /* Cart-related UI removed (cart-checkout feature deleted) */

        /* Buttons */
        .btn-accent {
            background-color: var(--accent);
            border-color: var(--accent);
            color: #fff;
            font-weight: 600;
            border-radius: 25px;
        }
        .btn-accent:hover {
            background-color: var(--accent-hover);
            border-color: var(--accent-hover);
            color: #fff;
        }
        .btn-outline-accent {
            border-color: var(--accent);
            color: var(--accent);
            font-weight: 600;
            border-radius: 25px;
        }
        .btn-outline-accent:hover {
            background-color: var(--accent);
            color: #fff;
        }

        /* Product Card */
        .product-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            background: #fff;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(244, 162, 97, 0.25);
        }
        .product-card .card-img-top {
            height: 200px;
            object-fit: cover;
            background-color: var(--accent-soft);
        }
        .product-card .img-placeholder {
            height: 200px;
            background: linear-gradient(135deg, var(--accent-soft) 0%, var(--warm-card) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 3rem;
        }
        .product-card .card-body {
            padding: 1rem;
        }
        .product-card .product-category {
            font-size: 0.78rem;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .product-card .product-name {
            font-weight: 600;
            font-size: 0.95rem;
            color: var(--text-dark);
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.8em;
        }
        .product-card .product-name a {
            color: inherit;
            text-decoration: none;
        }
        .product-card .product-name a:hover {
            color: var(--accent);
        }
        .product-card .price-current {
            font-weight: 700;
            font-size: 1.1rem;
            color: #e63946;
        }
        .product-card .price-original {
            text-decoration: line-through;
            color: #999;
            font-size: 0.85rem;
            margin-left: 6px;
        }
        .product-card .lead-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--accent);
            color: #fff;
            font-size: 0.7rem;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }

        /* Category Card */
        .category-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            background: linear-gradient(135deg, var(--warm-header), var(--accent-soft));
            color: var(--text-dark);
            text-align: center;
            padding: 2rem 1rem;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(244, 162, 97, 0.25);
        }
        .category-card i {
            font-size: 2.5rem;
            color: var(--accent);
            margin-bottom: 0.8rem;
        }
        .category-card h5 {
            font-weight: 700;
            margin-bottom: 0;
            color: var(--text-dark);
        }
        .category-card a {
            color: var(--text-dark);
            text-decoration: none;
        }

        /* Section Titles */
        .section-title {
            font-weight: 700;
            font-size: 1.6rem;
            color: var(--text-dark);
            position: relative;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--accent);
            border-radius: 2px;
        }

        /* Footer */
        .footer-pcshop {
            background-color: var(--text-dark);
            color: #f9e4cc;
            padding: 3rem 0 1.5rem;
        }
        .footer-pcshop h5 {
            color: var(--accent);
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: 'Pacifico', cursive;
        }
        .footer-pcshop a {
            color: #f9e4cc;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.2s;
        }
        .footer-pcshop a:hover {
            color: var(--accent);
        }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 1rem;
            margin-top: 2rem;
            text-align: center;
            color: #f9e4cc;
            font-size: 0.85rem;
        }

        /* Flash messages */
        .flash-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        }

        /* Sidebar */
        .sidebar-filter {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(244, 162, 97, 0.1);
        }
        .sidebar-filter h6 {
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: var(--text-dark);
        }
        .sidebar-filter .list-group-item {
            border: none;
            padding: 0.4rem 0;
            background: transparent;
        }
        .sidebar-filter .list-group-item a {
            color: var(--text-dark);
            text-decoration: none;
        }
        .sidebar-filter .list-group-item a:hover,
        .sidebar-filter .list-group-item a.active-filter {
            color: var(--accent);
            font-weight: 700;
        }

        /* Hero */
        .hero-banner {
            background: linear-gradient(135deg, var(--warm-header) 0%, var(--accent-soft) 50%, #fff5e6 100%);
            color: var(--text-dark);
            padding: 5rem 0;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(244,162,97,0.25) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-banner h1 {
            font-family: 'Pacifico', cursive;
            font-weight: 400;
            font-size: 3rem;
            margin-bottom: 1rem;
            line-height: 1.3;
        }
        .hero-banner .accent-text {
            color: var(--accent);
        }
        .hero-banner p {
            font-size: 1.15rem;
            color: var(--text-dark);
            margin-bottom: 2rem;
        }

        /* Pagination custom */
        .pagination .page-link {
            color: var(--text-dark);
            border-color: var(--accent-soft);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--accent);
            border-color: var(--accent);
        }

        /* Birthday cake spotlight section */
        .birthday-spotlight {
            background: linear-gradient(135deg, #ffe8d6 0%, #ffd8a8 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            margin: 2rem 0;
            text-align: center;
        }
        .birthday-spotlight h3 {
            font-family: 'Pacifico', cursive;
            color: var(--accent-hover);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .hero-banner h1 {
                font-size: 2rem;
            }
            .hero-banner {
                padding: 3rem 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Top Bar -->
    <div class="top-bar d-none d-md-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-telephone-fill me-1"></i> 0123.456.789
                <span class="mx-2">|</span>
                <i class="bi bi-envelope-fill me-1"></i> hello@happycake.vn
            </div>
            <div>
                <i class="bi bi-geo-alt-fill me-1"></i> 88 Hoa Hồng, Quận 1, TP. HCM
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-pcshop sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="bi bi-cake2-fill"></i>Happy Cake
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-label="Toggle navigation">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-fill me-1"></i>Trang chủ
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <!-- Auth -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                {{-- Admin links removed (admin-dashboard feature deleted) --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person-gear me-2"></i>Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button class="dropdown-item" type="submit">
                                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="flash-container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    <footer class="footer-pcshop mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5><i class="bi bi-cake2-fill me-2"></i>Happy Cake</h5>
                    <p>Tiệm bánh nướng tươi mỗi ngày, chuyên bánh kem sinh nhật, cupcake, bánh ngọt Pháp. Đặt trước - giao tận nhà - ghi chữ theo yêu cầu.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="fs-5"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-tiktok"></i></a>
                        <a href="#" class="fs-5"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4">
                    <h5>Liên kết</h5>
                    <a href="{{ route('home') }}">Trang chủ</a>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Chính sách</h5>
                    <a href="#">Đặt bánh & giao hàng</a>
                    <a href="#">Chính sách đổi trả</a>
                    <a href="#">Bảo quản bánh</a>
                    <a href="#">Bảo mật thông tin</a>
                </div>
                <div class="col-lg-3 col-md-4 mb-4">
                    <h5>Liên hệ</h5>
                    <p class="mb-2"><i class="bi bi-geo-alt-fill me-2"></i>88 Hoa Hồng, Quận 1, TP. HCM</p>
                    <p class="mb-2"><i class="bi bi-telephone-fill me-2"></i>0123.456.789</p>
                    <p class="mb-2"><i class="bi bi-envelope-fill me-2"></i>hello@happycake.vn</p>
                    <p class="mb-0"><i class="bi bi-clock-fill me-2"></i>7:00 - 22:00 (T2 - CN)</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} Happy Cake. Bánh ngon mỗi ngày 🍰</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-dismiss flash messages -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.flash-container .alert').forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 4000);
        });
    </script>

    @yield('scripts')
</body>
</html>
