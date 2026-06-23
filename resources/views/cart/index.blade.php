@extends('layouts.app')

@section('title', 'Giỏ hàng - Happy Cake')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active">Giỏ hàng</li>
        </ol>
    </nav>

    <h2 class="section-title">
        <i class="bi bi-basket3-fill me-2"></i>Giỏ bánh của bạn
    </h2>

    @php
        $cart = session('cart', []);
    @endphp

    @if(count($cart) > 0)
        <form action="{{ route('cart.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-3 shadow-sm overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 80px;">Hình</th>
                                <th>Bánh</th>
                                <th style="width: 140px;" class="text-end">Đơn giá</th>
                                <th style="width: 130px;" class="text-center">Số lượng</th>
                                <th style="width: 140px;" class="text-end">Thành tiền</th>
                                <th style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $total = 0; $maxLead = 1; @endphp
                            @foreach($cart as $key => $item)
                                @php
                                    $price = $item['price'];
                                    $subtotal = $price * $item['quantity'];
                                    $total += $subtotal;
                                    $lead = (int) ($item['min_lead_days'] ?? 1);
                                    if ($lead > $maxLead) $maxLead = $lead;
                                @endphp
                                <tr>
                                    <td>
                                        @if(isset($item['image']) && $item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="">
                                        @else
                                            <div class="rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #ffe8d6;">
                                                <i class="bi bi-cake2-fill" style="color: #f4a261;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <h6 class="mb-0 fw-semibold">{{ $item['name'] }}</h6>
                                        @if(!empty($item['size_label']))
                                            <small class="text-muted"><i class="bi bi-rulers me-1"></i>Size: {{ $item['size_label'] }}</small>
                                        @endif
                                        @if($lead >= 2)
                                            <small class="d-block text-warning"><i class="bi bi-clock-fill me-1"></i>Đặt trước {{ $lead }} ngày</small>
                                        @endif
                                    </td>
                                    <td class="text-end text-danger fw-bold">
                                        {{ number_format($price, 0, ',', '.') }}đ
                                    </td>
                                    <td class="text-center">
                                        <input type="number" name="quantity[{{ $key }}]" class="form-control form-control-sm text-center mx-auto" style="width: 80px;" value="{{ $item['quantity'] }}" min="1">
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ number_format($subtotal, 0, ',', '.') }}đ
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('cart.remove', $key) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa bánh này khỏi giỏ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($maxLead >= 2)
                <div class="alert alert-warning mt-3">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Đơn hàng của bạn có bánh cần làm trước. <strong>Ngày nhận sớm nhất:
                    {{ \Carbon\Carbon::today()->addDays($maxLead)->format('d/m/Y') }}</strong> ({{ $maxLead }} ngày từ hôm nay).
                </div>
            @endif

            <!-- Cart Actions -->
            <div class="row mt-4">
                <div class="col-lg-6 mb-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>Chọn thêm bánh
                    </a>
                    <button type="submit" class="btn btn-outline-accent ms-2">
                        <i class="bi bi-arrow-clockwise me-1"></i>Cập nhật giỏ
                    </button>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white rounded-3 p-4 shadow-sm">
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span class="h5 fw-bold mb-0">Tổng cộng:</span>
                            <span class="h4 fw-bold text-danger mb-0">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn btn-accent btn-lg w-100">
                            <i class="bi bi-credit-card me-2"></i>Tiến hành đặt bánh
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @else
        <div class="text-center py-5">
            <i class="bi bi-basket3" style="font-size: 5rem; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">Giỏ bánh đang trống</h4>
            <p class="text-muted">Bạn chưa chọn bánh nào. Hãy ghé thực đơn nhé!</p>
            <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg">
                <i class="bi bi-cake-fill me-2"></i>Xem thực đơn
            </a>
        </div>
    @endif
</div>
@endsection
