<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Happy Cake'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #fff8ef;
            --text: #4d2f1b;
            --accent: #d97706;
        }

        body {
            background: radial-gradient(circle at top, #fffdf8 0%, var(--bg) 55%, #ffe7c7 100%);
            color: var(--text);
        }

        .navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
        }

        .brand-mark {
            font-weight: 800;
            letter-spacing: 0.04em;
            color: var(--accent) !important;
        }

        .hero {
            background: linear-gradient(135deg, #fff4df, #ffffff 55%, #ffe3bc);
            border: 1px solid rgba(217, 119, 6, 0.15);
            border-radius: 1.5rem;
        }

        .soft-card {
            background: #ffffff;
            border: 1px solid rgba(77, 47, 27, 0.08);
            border-radius: 1.25rem;
            box-shadow: 0 18px 50px rgba(77, 47, 27, 0.08);
        }

        .btn-accent {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .btn-accent:hover {
            background: #b45309;
            border-color: #b45309;
            color: #fff;
        }

        .price-old {
            color: #9ca3af;
            text-decoration: line-through;
        }

        .product-image {
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, #fde68a, #fecaca);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top shadow-sm">
    <div class="container py-2">
        <a class="navbar-brand brand-mark" href="{{ route('home') }}">Happy Cake</a>
        <div class="ms-auto d-flex gap-2 align-items-center">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">Sản phẩm</a>
            <a href="{{ route('cart.index') }}" class="btn btn-accent btn-sm">
                <i class="bi bi-basket"></i> Giỏ hàng
                <span class="badge text-bg-light ms-1">{{ array_sum(array_column(session('cart', []), 'quantity')) }}</span>
            </a>
        </div>
    </div>
</nav>

<main class="container py-4 py-lg-5">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>