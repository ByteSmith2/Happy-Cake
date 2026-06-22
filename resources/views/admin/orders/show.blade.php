@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn bánh #' . $order->id)

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

    <div class="mb-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        {{-- Order Info --}}
        <div class="col-md-8">
            {{-- Customer Info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Họ tên:</strong> {{ $order->user->name ?? $order->name }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email ?? '-' }}</p>
                            <p><strong>Điện thoại:</strong> {{ $order->phone ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Địa chỉ giao:</strong> {{ $order->address ?? 'N/A' }}</p>
                            <p><strong>Ghi chú:</strong> {{ $order->note ?? 'Không có' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bakery-specific info --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-cake2-fill me-2" style="color: #f4a261;"></i>Thông tin bánh</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Ngày khách muốn nhận bánh</p>
                            <p class="fs-5 fw-bold" style="color: #f4a261;">
                                <i class="bi bi-calendar-event me-1"></i>{{ $order->delivery_date->format('d/m/Y (l)') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Lời nhắn ghi trên bánh</p>
                            @if($order->cake_message)
                                <p class="fs-5 fw-bold" style="color: #5b3a1e;">
                                    <i class="bi bi-chat-heart-fill me-1"></i>"{{ $order->cake_message }}"
                                </p>
                            @else
                                <p class="text-muted fst-italic">— Không có —</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Bánh trong đơn</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Bánh</th>
                                    <th>Size</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product && $item->product->image)
                                                    <img src="{{ asset('storage/' . $item->product->image) }}" alt="" class="img-thumbnail me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                                {{ $item->product->name ?? 'Bánh đã xóa' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($item->size_label)
                                                <span class="badge bg-warning text-dark">{{ $item->size_label }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                    <td class="text-end"><strong class="text-danger fs-5">{{ number_format($order->total_price, 0, ',', '.') }}đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Update --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Trạng thái đơn</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p><strong>Mã đơn:</strong> #{{ $order->id }}</p>
                        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Ngày nhận:</strong> <span style="color: #f4a261;">{{ $order->delivery_date->format('d/m/Y') }}</span></p>
                        <p>
                            <strong>Trạng thái:</strong>
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                                {{ $statusLabels[$order->status] ?? $order->status }}
                            </span>
                        </p>
                    </div>

                    <hr>

                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="status" class="form-label">Cập nhật trạng thái</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="pending"   {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="baking"    {{ $order->status == 'baking' ? 'selected' : '' }}>Đang làm bánh</option>
                                <option value="shipping"  {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-lg"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
