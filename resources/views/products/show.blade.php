@extends('layouts.app')

@section('title', $product->name . ' - Happy Cake')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Thực đơn</a></li>
            @if($product->category)
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.products', $product->category->slug) }}" class="text-decoration-none">{{ $product->category->name }}</a>
                </li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Detail -->
    <div class="row">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4">
            <div class="bg-white rounded-3 p-3 shadow-sm">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}" style="width: 100%; height: 400px; object-fit: cover;">
                @else
                    <div class="d-flex align-items-center justify-content-center rounded" style="height: 400px; background: linear-gradient(135deg, #ffe8d6, #ffd8a8);">
                        <i class="bi bi-cake2-fill" style="font-size: 6rem; color: #f4a261;"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7 mb-4">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                @if($product->category)
                    <a href="{{ route('categories.products', $product->category->slug) }}" class="text-decoration-none">
                        <span class="badge bg-warning text-dark mb-2">{{ $product->category->name }}</span>
                    </a>
                @endif

                <h1 class="h3 fw-bold mb-3">{{ $product->name }}</h1>

                <!-- Price -->
                <div class="mb-3 pb-3 border-bottom">
                    @if($product->hasSizeOptions())
                        @php
                            $minPrice = collect($product->size_options)->min('price');
                            $maxPrice = collect($product->size_options)->max('price');
                        @endphp
                        <span class="h4 fw-bold text-danger">
                            {{ number_format($minPrice, 0, ',', '.') }}đ
                            @if($minPrice != $maxPrice)
                                - {{ number_format($maxPrice, 0, ',', '.') }}đ
                            @endif
                        </span>
                        <small class="d-block text-muted">Giá thay đổi theo size bạn chọn</small>
                    @elseif($product->sale_price)
                        <span class="h3 fw-bold text-danger">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                        <span class="h5 text-muted text-decoration-line-through ms-2">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        @php
                            $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                        @endphp
                        <span class="badge bg-danger ms-2">-{{ $discount }}%</span>
                    @else
                        <span class="h3 fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                    @endif
                </div>

                <!-- Lead time notice -->
                @if($product->min_lead_days >= 2)
                    <div class="alert alert-warning d-flex align-items-center">
                        <i class="bi bi-clock-fill fs-4 me-2"></i>
                        <div>
                            <strong>Đặt trước tối thiểu {{ $product->min_lead_days }} ngày.</strong><br>
                            <small>Bánh được làm theo đơn, cần thời gian chuẩn bị và trang trí.</small>
                        </div>
                    </div>
                @endif

                <!-- Stock Status -->
                <div class="mb-3">
                    <span class="fw-semibold">Tình trạng:</span>
                    @if($product->stock > 0)
                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Còn nhận đơn ({{ $product->stock }})</span>
                    @else
                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Tạm hết</span>
                    @endif
                </div>

                <!-- Add to Cart -->
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                        @csrf

                        @if($product->hasSizeOptions())
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Chọn size <span class="text-danger">*</span></label>
                                <div class="d-flex flex-column gap-2">
                                    @foreach($product->size_options as $i => $size)
                                        <label class="border rounded-3 p-3 d-flex justify-content-between align-items-center" style="cursor: pointer;">
                                            <div>
                                                <input class="form-check-input me-2" type="radio" name="size" value="{{ $size['key'] }}" {{ $i == 0 ? 'checked' : '' }} required>
                                                <strong>{{ $size['label'] }}</strong>
                                            </div>
                                            <span class="fw-bold text-danger">{{ number_format($size['price'], 0, ',', '.') }}đ</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row align-items-end g-3">
                            <div class="col-auto">
                                <label for="quantity" class="form-label fw-semibold">Số lượng</label>
                                <div class="input-group" style="width: 140px;">
                                    <button type="button" class="btn btn-outline-secondary" onclick="changeQty(-1)">-</button>
                                    <input type="number" name="quantity" id="quantity" class="form-control text-center" value="1" min="1" max="{{ $product->stock }}">
                                    <button type="button" class="btn btn-outline-secondary" onclick="changeQty(1)">+</button>
                                </div>
                            </div>
                            <div class="col">
                                <button type="submit" class="btn btn-accent btn-lg">
                                    <i class="bi bi-basket-fill me-2"></i>Thêm vào giỏ
                                </button>
                            </div>
                        </div>
                    </form>
                @endif

                <!-- Short Info -->
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-truck me-2 fs-5" style="color: #f4a261;"></i>
                            <small>Giao tận nhà nội thành</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-chat-quote-fill me-2 fs-5" style="color: #f4a261;"></i>
                            <small>Ghi chữ theo yêu cầu</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-egg-fried me-2 fs-5" style="color: #f4a261;"></i>
                            <small>Bánh tươi mỗi ngày</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm">
                <h4 class="fw-bold mb-3">
                    <i class="bi bi-file-text me-2" style="color: #f4a261;"></i>Mô tả bánh
                </h4>
                <div class="product-description">
                    {!! nl2br(e($product->description)) !!}
                </div>
                @if(!$product->description)
                    <p class="text-muted">Chưa có mô tả cho bánh này.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="mt-5">
        <h3 class="section-title">Bánh cùng loại</h3>
        <div class="row g-4">
            @foreach($relatedProducts as $relProduct)
                <div class="col-6 col-md-3">
                    @include('partials.product-card', ['product' => $relProduct])
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection

@section('scripts')
<script>
    function changeQty(delta) {
        var input = document.getElementById('quantity');
        var val = parseInt(input.value) + delta;
        var max = parseInt(input.max);
        if (val >= 1 && val <= max) {
            input.value = val;
        }
    }
</script>
@endsection
