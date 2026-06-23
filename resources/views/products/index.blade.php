@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - Happy Cake' : 'Thực đơn bánh - Happy Cake')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            @if(isset($category))
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}" class="text-decoration-none">Thực đơn</a></li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            @else
                <li class="breadcrumb-item active">Thực đơn</li>
            @endif
        </ol>
    </nav>

    <!-- Page Title -->
    <h2 class="section-title">
        @if(isset($category))
            {{ $category->name }}
        @else
            Toàn bộ thực đơn bánh
        @endif
    </h2>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm bánh..." value="{{ request('search') }}">
                    <button class="btn btn-accent" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-lg-4 mt-2 mt-lg-0">
            <form action="{{ isset($category) ? route('categories.products', $category->slug) : route('products.index') }}" method="GET" id="sortForm">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif
                @if(request('price_from'))
                    <input type="hidden" name="price_from" value="{{ request('price_from') }}">
                @endif
                @if(request('price_to'))
                    <input type="hidden" name="price_to" value="{{ request('price_to') }}">
                @endif
                <select name="sort" class="form-select" onchange="document.getElementById('sortForm').submit()">
                    <option value="">Sắp xếp</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến cao</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến thấp</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên: A-Z</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="sidebar-filter mb-4">
                <h6><i class="bi bi-funnel me-2"></i>Danh mục</h6>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <a href="{{ route('products.index') }}" class="{{ !isset($category) ? 'active-filter' : '' }}">
                            Tất cả
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li class="list-group-item">
                            <a href="{{ route('categories.products', $cat->slug) }}"
                               class="{{ isset($category) && $category->id == $cat->id ? 'active-filter' : '' }}">
                                {{ $cat->name }}
                                <span class="text-muted float-end">({{ $cat->products_count ?? $cat->products->count() }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="sidebar-filter">
                <h6><i class="bi bi-currency-dollar me-2"></i>Lọc theo giá</h6>
                <form action="{{ isset($category) ? route('categories.products', $category->slug) : route('products.index') }}" method="GET">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif
                    <div class="mb-2">
                        <label class="form-label small">Từ (đ)</label>
                        <input type="number" name="price_from" class="form-control form-control-sm" placeholder="0" value="{{ request('price_from') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Đến (đ)</label>
                        <input type="number" name="price_to" class="form-control form-control-sm" placeholder="50.000.000" value="{{ request('price_to') }}">
                    </div>
                    <button type="submit" class="btn btn-accent btn-sm w-100">
                        <i class="bi bi-funnel-fill me-1"></i>Áp dụng
                    </button>
                </form>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            @if($products->count() > 0)
                <div class="row g-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">
                            @include('partials.product-card', ['product' => $product])
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">Không tìm thấy loại bánh nào</h5>
                    <p class="text-muted">Hãy thử tìm với từ khóa khác hoặc xem toàn bộ thực đơn.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-accent">
                        <i class="bi bi-arrow-left me-1"></i>Xem tất cả bánh
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
