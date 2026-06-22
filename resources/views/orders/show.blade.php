@extends('layouts.app')

@section('title', 'Đơn bánh #' . $order->id . ' - Happy Cake')

@section('content')
@php
    $statusLabels = [
        'pending'   => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'baking'    => 'Đang làm bánh',
        'shipping'  => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];
    $statusColors = [
        'pending'   => 'warning',
        'confirmed' => 'info',
        'baking'    => 'primary',
        'shipping'  => 'primary',
        'completed' => 'success',
        'cancelled' => 'danger',
    ];
@endphp

<div class="container py-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Đơn bánh</a></li>
            <li class="breadcrumb-item active">Đơn bánh #{{ $order->id }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="section-title mb-0">
            Đơn bánh #{{ $order->id }}
        </h2>
        <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6 px-3 py-2">
            {{ $statusLabels[$order->status] ?? $order->status }}
        </span>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-lg-4 mb-4">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-2" style="color: #f4a261;"></i>Thông tin đơn
                </h5>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted ps-0">Mã đơn:</td>
                        <td class="fw-bold pe-0">#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Ngày đặt:</td>
                        <td class="pe-0">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0"><i class="bi bi-calendar-event me-1"></i>Ngày nhận:</td>
                        <td class="fw-bold pe-0" style="color: #f4a261;">{{ $order->delivery_date->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted ps-0">Tổng tiền:</td>
                        <td class="fw-bold text-danger pe-0">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="col-lg-8 mb-4">
            <div class="bg-white rounded-3 p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-3">
                    <i class="bi bi-person me-2" style="color: #f4a261;"></i>Thông tin nhận bánh
                </h5>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><span class="text-muted">Họ tên:</span> <strong>{{ $order->name }}</strong></p>
                        <p class="mb-2"><span class="text-muted">Điện thoại:</span> <strong>{{ $order->phone }}</strong></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><span class="text-muted">Địa chỉ:</span> {{ $order->address }}</p>
                        @if($order->note)
                            <p class="mb-0"><span class="text-muted">Ghi chú:</span> {{ $order->note }}</p>
                        @endif
                    </div>
                </div>

                @if($order->cake_message)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="bi bi-chat-heart-fill me-2"></i>
                        <strong>Lời nhắn trên bánh:</strong> "{{ $order->cake_message }}"
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="bg-white rounded-3 shadow-sm overflow-hidden">
        <h5 class="fw-bold p-4 pb-2">
            <i class="bi bi-cake2-fill me-2" style="color: #f4a261;"></i>Bánh đã đặt
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Hình</th>
                        <th>Bánh</th>
                        <th class="text-end">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset('storage/' . $item->product->image) }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;" alt="">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background: #ffe8d6;">
                                        <i class="bi bi-cake2-fill" style="color: #f4a261;"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($item->product)
                                    <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none fw-semibold" style="color: #5b3a1e;">
                                        {{ $item->product->name }}
                                    </a>
                                @else
                                    <span class="text-muted">Bánh đã bị xóa</span>
                                @endif
                                @if($item->size_label)
                                    <small class="d-block text-muted"><i class="bi bi-rulers me-1"></i>Size: {{ $item->size_label }}</small>
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end fw-bold h5 mb-0">Tổng cộng:</td>
                        <td class="text-end fw-bold h5 mb-0 text-danger">{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách đơn
        </a>
    </div>
</div>
@endsection
