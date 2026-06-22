@extends('admin.layouts.app')

@section('title', 'Đơn đặt bánh')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Danh sách đơn đặt bánh</h4>
    </div>

    {{-- Filter by Status --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label">Lọc theo trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="pending"   {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="baking"    {{ request('status') == 'baking' ? 'selected' : '' }}>Đang làm bánh</option>
                        <option value="shipping"  {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg"></i> Xóa lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Orders Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Ngày đặt</th>
                            <th>Ngày nhận</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
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
                        @forelse($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->user->name ?? $order->name }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td><span class="fw-semibold" style="color: #f4a261;">{{ $order->delivery_date->format('d/m/Y') }}</span></td>
                                <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                <td>
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ $statusLabels[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> Xem
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

            @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
