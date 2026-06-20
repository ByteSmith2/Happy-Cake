@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    {{-- Stat Cards --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Sản phẩm bánh</h6>
                            <h3 class="mb-0">{{ number_format($totalProducts ?? 0) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-25 p-3 rounded">
                            <i class="bi bi-cake-fill fs-4" style="color: #f4a261;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Đơn đặt bánh</h6>
                            <h3 class="mb-0">{{ number_format($totalOrders ?? 0) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-bag-heart-fill text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Khách hàng</h6>
                            <h3 class="mb-0">{{ number_format($totalUsers ?? 0) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Doanh thu</h6>
                            <h3 class="mb-0">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded">
                            <i class="bi bi-currency-dollar text-danger fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Status Counts --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thống kê trạng thái đơn</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col">
                            <span class="badge bg-warning fs-6 mb-1">{{ $ordersByStatus['pending'] ?? 0 }}</span>
                            <p class="text-muted mb-0">Chờ xác nhận</p>
                        </div>
                        <div class="col">
                            <span class="badge bg-info fs-6 mb-1">{{ $ordersByStatus['confirmed'] ?? 0 }}</span>
                            <p class="text-muted mb-0">Đã xác nhận</p>
                        </div>
                        <div class="col">
                            <span class="badge bg-primary fs-6 mb-1">{{ $ordersByStatus['baking'] ?? 0 }}</span>
                            <p class="text-muted mb-0">Đang làm bánh</p>
                        </div>
                        <div class="col">
                            <span class="badge bg-primary fs-6 mb-1">{{ $ordersByStatus['shipping'] ?? 0 }}</span>
                            <p class="text-muted mb-0">Đang giao</p>
                        </div>
                        <div class="col">
                            <span class="badge bg-success fs-6 mb-1">{{ $ordersByStatus['completed'] ?? 0 }}</span>
                            <p class="text-muted mb-0">Hoàn thành</p>
                        </div>
                        <div class="col">
                            <span class="badge bg-danger fs-6 mb-1">{{ $ordersByStatus['cancelled'] ?? 0 }}</span>
                            <p class="text-muted mb-0">Đã hủy</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Đơn đặt bánh gần đây</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Ngày nhận</th>
                                    <th>Tổng tiền</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày đặt</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders ?? [] as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name ?? $order->name }}</td>
                                        <td><span style="color: #f4a261;" class="fw-semibold">{{ $order->delivery_date->format('d/m/Y') }}</span></td>
                                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        <td>
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
                                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                                {{ $statusLabels[$order->status] ?? $order->status }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Chưa có đơn bánh nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
