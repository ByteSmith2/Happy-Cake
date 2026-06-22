@extends('layouts.store')

@section('title', 'Happy Cake | Đặt hàng thành công')

@section('content')
    <div class="soft-card p-4 p-lg-5 text-center mx-auto" style="max-width: 760px;">
        <div class="display-6 mb-3">Đặt hàng thành công</div>
        <p class="text-secondary mb-4">Mã đơn #{{ $order->id }} đã được tạo. Chúng tôi sẽ chuẩn bị bánh theo ngày bạn đã chọn.</p>

        <div class="row g-3 text-start mb-4">
            <div class="col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="small text-secondary">Người nhận</div>
                    <div class="fw-semibold">{{ $order->name }}</div>
                    <div>{{ $order->phone }}</div>
                    <div>{{ $order->address }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded-3 p-3 h-100">
                    <div class="small text-secondary">Ngày nhận</div>
                    <div class="fw-semibold">{{ $order->delivery_date?->format('d/m/Y') }}</div>
                    <div class="small text-secondary mt-2">Tổng tiền</div>
                    <div class="fw-semibold text-danger">{{ number_format($order->total_price) }} đ</div>
                </div>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn btn-accent">Về trang chủ</a>
    </div>
@endsection