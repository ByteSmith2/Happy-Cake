@extends('layouts.app')

@section('title', 'Đặt bánh - Happy Cake')

@section('content')
<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Giỏ bánh</a></li>
            <li class="breadcrumb-item active">Đặt bánh</li>
        </ol>
    </nav>

    <h2 class="section-title">
        <i class="bi bi-bag-heart-fill me-2"></i>Đặt bánh
    </h2>

    @php
        $cart = session('cart', []);
        $total = 0;
    @endphp

    @if(count($cart) == 0)
        <div class="text-center py-5">
            <i class="bi bi-basket3" style="font-size: 5rem; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">Giỏ bánh trống</h4>
            <p class="text-muted">Vui lòng thêm bánh vào giỏ trước khi đặt.</p>
            <a href="{{ route('products.index') }}" class="btn btn-accent">
                <i class="bi bi-cake-fill me-2"></i>Xem thực đơn
            </a>
        </div>
    @else
        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Billing Form -->
                <div class="col-lg-7 mb-4">
                    <div class="bg-white rounded-3 p-4 shadow-sm">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-person-lines-fill me-2" style="color: #f4a261;"></i>Thông tin nhận bánh
                        </h5>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $user->phone ?? '') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-semibold">Địa chỉ giao bánh <span class="text-danger">*</span></label>
                            <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror" required>{{ old('address', $user->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NÂNG CAO: delivery_date input --}}
                        <div class="mb-3">
                            <label for="delivery_date" class="form-label fw-semibold">
                                <i class="bi bi-calendar-event me-1"></i>Ngày nhận bánh <span class="text-danger">*</span>
                            </label>
                            <input type="date" name="delivery_date" id="delivery_date"
                                   class="form-control @error('delivery_date') is-invalid @enderror"
                                   value="{{ old('delivery_date', $earliestDeliveryDate) }}"
                                   min="{{ $earliestDeliveryDate }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-1">
                                <i class="bi bi-info-circle me-1"></i>
                                Đơn hàng cần chuẩn bị trước <strong>{{ $minLeadDays }} ngày</strong>.
                                Ngày nhận sớm nhất: <strong>{{ \Carbon\Carbon::parse($earliestDeliveryDate)->format('d/m/Y') }}</strong>
                            </small>
                        </div>

                        {{-- NÂNG CAO: cake_message input --}}
                        <div class="mb-3">
                            <label for="cake_message" class="form-label fw-semibold">
                                <i class="bi bi-chat-heart me-1"></i>Lời nhắn ghi trên bánh
                            </label>
                            <input type="text" name="cake_message" id="cake_message"
                                   class="form-control @error('cake_message') is-invalid @enderror"
                                   value="{{ old('cake_message') }}" maxlength="200"
                                   placeholder="Ví dụ: Chúc mừng sinh nhật Mai - 20 tuổi">
                            @error('cake_message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Để trống nếu không cần ghi chữ. Tối đa 200 ký tự.</small>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fw-semibold">Ghi chú khác</label>
                            <textarea name="note" id="note" rows="2" class="form-control" placeholder="Ghi chú thêm về đơn hàng (ví dụ: ít ngọt, không đậu phộng, giao trước 10h sáng...)">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-5 mb-4">
                    <div class="bg-white rounded-3 p-4 shadow-sm" style="position: sticky; top: 90px;">
                        <h5 class="fw-bold mb-4">
                            <i class="bi bi-receipt me-2" style="color: #f4a261;"></i>Đơn bánh của bạn
                        </h5>

                        <div class="border-bottom pb-3 mb-3">
                            @foreach($cart as $key => $item)
                                @php
                                    $price = $item['price'];
                                    $subtotal = $price * $item['quantity'];
                                    $total += $subtotal;
                                @endphp
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-start">
                                        @if(isset($item['image']) && $item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;" alt="">
                                        @else
                                            <div class="rounded me-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background: #ffe8d6;">
                                                <i class="bi bi-cake2-fill" style="color: #f4a261;"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 small fw-semibold">{{ $item['name'] }}</h6>
                                            @if(!empty($item['size_label']))
                                                <small class="text-muted d-block">Size: {{ $item['size_label'] }}</small>
                                            @endif
                                            <small class="text-muted">x{{ $item['quantity'] }}</small>
                                        </div>
                                    </div>
                                    <span class="fw-bold text-nowrap">{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Tạm tính:</span>
                            <span class="fw-bold">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <span>Phí giao hàng:</span>
                            <span class="text-success fw-semibold">Miễn phí nội thành</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="h5 fw-bold mb-0">Tổng cộng:</span>
                            <span class="h4 fw-bold text-danger mb-0">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>

                        <button type="submit" class="btn btn-accent btn-lg w-100">
                            <i class="bi bi-check-circle me-2"></i>Đặt bánh ngay
                        </button>

                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left me-1"></i>Quay lại giỏ bánh
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
