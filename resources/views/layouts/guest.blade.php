<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Happy Cake') }} - Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&family=Pacifico&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffd8a8 0%, #ffe8d6 50%, #fff5e6 100%);
            min-height: 100vh;
            font-family: 'Quicksand', sans-serif;
        }
        .auth-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 40px rgba(244, 162, 97, 0.3); }
        .btn-primary { background: #f4a261; border-color: #f4a261; border-radius: 25px; font-weight: 600; }
        .btn-primary:hover { background: #e08c4a; border-color: #e08c4a; }
        a { color: #f4a261; }
        .brand-logo { font-family: 'Pacifico', cursive; color: #f4a261; }
    </style>
</head>
<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-card p-4 p-md-5" style="width: 100%; max-width: 450px;">
            <div class="text-center mb-4">
                <a href="/" class="text-decoration-none">
                    <h2 class="brand-logo">🍰 Happy Cake</h2>
                </a>
            </div>
            {{ $slot }}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
