@extends('layouts.store')

@section('title', 'Happy Cake | Sản phẩm')

@section('content')
    <div class="hero p-4 p-lg-5 mb-4 soft-card">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <h1 class="display-6 fw-bold mb-3">Chọn bánh, thêm vào giỏ, đặt ngày giao.</h1>
                <p class="lead mb-0">Dự án này chỉ giữ lại phần bán hàng cốt lõi: xem sản phẩm, thêm vào giỏ và thanh toán đặt trước.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('cart.index') }}" class="btn btn-accent btn-lg">Đi tới giỏ hàng</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-12 col-md-6 col-xl-4">
                <div class="soft-card h-100 overflow-hidden">
                    @if ($product->image)
                        <img src="{{ asset($product->image) }}" class="w-100 product-image" alt="{{ $product->name }}">
                    @else
                        <div class="product-image d-flex align-items-center justify-content-center text-muted">
                            <i class="bi bi-cake2-fill fs-1"></i>
                        </div>
                    @endif
                    <div class="p-3 p-lg-4 d-flex flex-column gap-3">
                        <div>
                            <div class="small text-uppercase text-secondary">{{ $product->category?->name ?? 'Bánh' }}</div>
                            <h2 class="h5 mb-2">{{ $product->name }}</h2>
                            <div>
                                <span class="fw-bold text-danger fs-5">{{ number_format($product->display_price) }} đ</span>
                                @if ($product->sale_price)
                                    <span class="price-old ms-2">{{ number_format($product->price) }} đ</span>
                                @endif
                            </div>
                        </div>

                        <p class="text-secondary mb-0">{{ $product->description }}</p>

                        <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-auto d-grid gap-2">
                            @csrf
                            @if ($product->hasSizeOptions())
                                <select name="size" class="form-select" required>
                                    <option value="">Chọn size</option>
                                    @foreach ($product->size_options as $size)
                                        <option value="{{ $size['key'] }}">{{ $size['label'] }} - {{ number_format($size['price']) }} đ</option>
                                    @endforeach
                                </select>
                            @endif
                            <button type="submit" class="btn btn-accent">Thêm vào giỏ</button>
                        </form>

                        <div class="small text-secondary">Đặt trước tối thiểu {{ $product->min_lead_days ?? 1 }} ngày</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning mb-0">Chưa có sản phẩm nào trong hệ thống.</div>
            </div>
        @endforelse
    </div>
@endsection