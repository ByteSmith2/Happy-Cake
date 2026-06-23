@extends('layouts.app')

@section('title', 'Happy Cake - Tiệm bánh đặt trước, giao tận nhà')

@section('content')
    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1>
                        <span class="accent-text">Happy Cake</span><br>
                        Bánh ngọt từ trái tim 🍰
                    </h1>
                    <p>Tiệm bánh nướng tươi mỗi ngày. Đặt bánh sinh nhật trước 3 ngày, ghi chữ theo yêu cầu, giao tận nhà nội thành TP. HCM.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg px-4">
                        <i class="bi bi-cake-fill me-2"></i>Xem thực đơn
                    </a>
                    <a href="{{ route('categories.products', 'banh-sinh-nhat') }}" class="btn btn-outline-accent btn-lg px-4 ms-2">
                        <i class="bi bi-gift-fill me-2"></i>Đặt bánh sinh nhật
                    </a>
                </div>
                <div class="col-lg-5 text-center d-none d-lg-block">
                    <i class="bi bi-cake2-fill" style="font-size: 12rem; color: rgba(244, 162, 97, 0.5);"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title">Danh mục bánh</h2>
            <div class="row g-4">
                @foreach($categories as $category)
                    @php
                        $icons = [
                            'banh-sinh-nhat' => 'bi-gift-fill',
                            'banh-kem' => 'bi-cake2-fill',
                            'cupcake' => 'bi-cup-hot-fill',
                            'banh-mi' => 'bi-basket-fill',
                            'banh-ngot' => 'bi-cookie',
                            'banh-trung-thu' => 'bi-moon-stars-fill',
                        ];
                        $icon = $icons[$category->slug] ?? 'bi-cake-fill';
                    @endphp
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="category-card">
                            <a href="{{ route('categories.products', $category->slug) }}">
                                <i class="bi {{ $icon }} d-block"></i>
                                <h5>{{ $category->name }}</h5>
                                <small>{{ $category->products_count ?? $category->products->count() }} loại</small>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Birthday Cake Spotlight (NÂNG CAO) -->
    @if(isset($birthdayCakes) && $birthdayCakes->count() > 0)
    <section class="py-4">
        <div class="container">
            <div class="birthday-spotlight">
                <h3><i class="bi bi-gift-fill me-2"></i>Đặt bánh sinh nhật custom</h3>
                <p class="mb-4">Bánh kem tự tay trang trí - ghi chữ theo yêu cầu - đặt trước tối thiểu 3 ngày</p>
                <div class="row g-4">
                    @foreach($birthdayCakes as $cake)
                        <div class="col-6 col-md-3">
                            @include('partials.product-card', ['product' => $cake])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Products -->
    @if(isset($featuredProducts) && $featuredProducts->count() > 0)
    <section class="py-5 bg-white">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Bánh bán chạy</h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-accent btn-sm">
                    Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Latest Products -->
    @if(isset($latestProducts) && $latestProducts->count() > 0)
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="section-title mb-0">Bánh mới ra lò</h2>
                <a href="{{ route('products.index') }}" class="btn btn-outline-accent btn-sm">
                    Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="row g-4">
                @foreach($latestProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
