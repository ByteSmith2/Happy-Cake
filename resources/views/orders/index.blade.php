@extends('layouts.app')

@section('title', 'Đơn bánh của tôi - Happy Cake')

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
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active">Đơn bánh của tôi</li>
        </ol>
    </nav>

    <h2 class="section-title">
        <i class="bi bi-bag-heart-fill me-2"></i>Đơn bánh của tôi
    </h2>

    @if($orders->count() > 0)
        <div class="bg-white rounded-3 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Ngày nhận</th>
                            <th class="text-end">Tổng tiền</th>
                            <th class="text-center">Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold">#{{ $order->id }}</span>
                                </td>
                                <td>
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <span class="fw-semibold" style="color: #f4a261;">
                                        <i class="bi bi-calendar-event me-1"></i>{{ $order->delivery_date->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-danger">
                                    {{ number_format($order->total_price, 0, ',', '.') }}đ
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-accent btn-sm">
                                        <i class="bi bi-eye me-1"></i>Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-bag-x" style="font-size: 5rem; color: #ccc;"></i>
            <h4 class="mt-3 text-muted">Chưa có đơn bánh nào</h4>
            <p class="text-muted">Bạn chưa đặt bánh lần nào. Hãy ghé thực đơn nhé!</p>
            <a href="{{ route('products.index') }}" class="btn btn-accent btn-lg">
                <i class="bi bi-cake-fill me-2"></i>Xem thực đơn
            </a>
        </div>
    @endif
</div>
@endsection
