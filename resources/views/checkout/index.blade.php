@extends('layouts.store')

@section('title', 'Happy Cake | Thanh toán')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="soft-card p-3 p-lg-4">
                <h1 class="h3 mb-3">Thanh toán</h1>
                <p class="text-secondary">Ngày giao sớm nhất cho giỏ hàng hiện tại là <strong>{{ $earliestDate }}</strong> (cần đặt trước {{ $minLeadDays }} ngày).</p>

                <form action="{{ route('checkout.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-6">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Địa chỉ giao hàng</label>
                        <textarea name="address" class="form-control" rows="2" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày nhận bánh</label>
                        <input type="date" name="delivery_date" class="form-control" value="{{ old('delivery_date', $earliestDate) }}" min="{{ $earliestDate }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lời nhắn trên bánh</label>
                        <input type="text" name="cake_message" class="form-control" value="{{ old('cake_message') }}" maxlength="200">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ghi chú thêm</label>
                        <textarea name="note" class="form-control" rows="3">{{ old('note') }}</textarea>
                    </div>
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-accent btn-lg">Đặt hàng</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="soft-card p-3 p-lg-4 position-sticky" style="top: 90px;">
                <h2 class="h5 mb-3">Tóm tắt đơn hàng</h2>
                @foreach ($items as $item)
                    <div class="d-flex justify-content-between gap-3 mb-2">
                        <div>
                            <div class="fw-semibold">{{ $item['name'] }}</div>
                            <div class="small text-secondary">x{{ $item['quantity'] }} @if (!empty($item['size_label'])) - {{ $item['size_label'] }} @endif</div>
                        </div>
                        <div class="text-end">{{ number_format($item['price'] * $item['quantity']) }} đ</div>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fs-5">Tổng cộng</span>
                    <strong class="fs-4 text-danger">{{ number_format($total) }} đ</strong>
                </div>
            </div>
        </div>
    </div>
@endsection