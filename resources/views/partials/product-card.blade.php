<div class="card product-card position-relative">
    @if($product->min_lead_days >= 3)
        <span class="lead-badge"><i class="bi bi-clock-fill me-1"></i>Đặt trước {{ $product->min_lead_days }} ngày</span>
    @endif
    <a href="{{ route('products.show', $product->slug) }}">
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
        @else
            <div class="img-placeholder">
                <i class="bi bi-cake2-fill"></i>
            </div>
        @endif
    </a>
    <div class="card-body d-flex flex-column">
        <span class="product-category">{{ $product->category->name ?? '' }}</span>
        <h6 class="product-name mt-1">
            <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
        </h6>
        <div class="mt-auto">
            <div class="mb-2">
                @if($product->sale_price)
                    <span class="price-current">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                    <span class="price-original">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                @else
                    <span class="price-current">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                @endif
                @if($product->hasSizeOptions())
                    <small class="d-block text-muted">Có nhiều size</small>
                @endif
            </div>
            @if($product->hasSizeOptions())
                {{-- Nếu có size variants, bắt người dùng vào trang chi tiết để chọn --}}
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-accent btn-sm w-100">
                    <i class="bi bi-eye me-1"></i>Chọn size & đặt
                </a>
            @else
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-accent btn-sm w-100">
                        <i class="bi bi-basket3-fill me-1"></i>Thêm vào giỏ
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
