@extends('layouts.store')

@section('title', 'Happy Cake | Giỏ hàng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Giỏ hàng</h1>
            <p class="text-secondary mb-0">Kiểm tra số lượng và điều chỉnh trước khi thanh toán.</p>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">Tiếp tục mua hàng</a>
    </div>

    @if ($items->isEmpty())
        <div class="soft-card p-4 text-center">
            <p class="mb-3">Giỏ hàng đang trống.</p>
            <a href="{{ route('home') }}" class="btn btn-accent">Xem sản phẩm</a>
        </div>
    @else
        <form action="{{ route('cart.update') }}" method="POST" class="row g-4">
            @csrf
            @method('PUT')
            <div class="col-lg-8">
                <div class="soft-card p-3 p-lg-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Tạm tính</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $item['name'] }}</div>
                                            @if (!empty($item['size_label']))
                                                <div class="small text-secondary">{{ $item['size_label'] }}</div>
                                            @endif
                                            <div class="small text-secondary">{{ number_format($item['price']) }} đ / sản phẩm</div>
                                        </td>
                                        <td class="text-center" style="width: 130px;">
                                            <input type="number" min="1" name="items[{{ $item['key'] }}][quantity]" value="{{ $item['quantity'] }}" class="form-control text-center">
                                        </td>
                                        <td class="text-end fw-semibold">{{ number_format($item['subtotal']) }} đ</td>
                                        <td class="text-end">
                                            <a href="{{ route('cart.remove', $item['key']) }}" class="btn btn-sm btn-outline-danger">Xóa</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-secondary">Cập nhật giỏ hàng</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="soft-card p-3 p-lg-4 position-sticky" style="top: 90px;">
                    <h2 class="h5">Tổng đơn</h2>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền</span>
                        <strong>{{ number_format($total) }} đ</strong>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="btn btn-accent w-100">Đi tới thanh toán</a>
                </div>
            </div>
        </form>
    @endif
@endsection