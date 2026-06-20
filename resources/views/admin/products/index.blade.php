@extends('admin.layouts.app')

@section('title', 'Quản lý bánh')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Danh sách bánh</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Thêm bánh
        </a>
    </div>

    {{-- Filters --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="search" class="form-label">Tìm theo tên bánh</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nhập tên bánh...">
                </div>
                <div class="col-md-3">
                    <label for="category_id" class="form-label">Danh mục</label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">-- Tất cả danh mục --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search"></i> Lọc
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-x-lg"></i> Xóa lọc
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Products Table --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Hình</th>
                            <th>Tên bánh</th>
                            <th>Danh mục</th>
                            <th>Giá</th>
                            <th>Giá KM</th>
                            <th>Size</th>
                            <th>Lead time</th>
                            <th>Tồn kho</th>
                            <th>Nổi bật</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->category->name ?? 'N/A' }}</span>
                                </td>
                                <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                <td>
                                    @if($product->sale_price)
                                        <span class="text-danger">{{ number_format($product->sale_price, 0, ',', '.') }}đ</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->hasSizeOptions())
                                        <span class="badge bg-warning text-dark">{{ count($product->size_options) }} size</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->min_lead_days >= 2)
                                        <span class="badge bg-info"><i class="bi bi-clock me-1"></i>{{ $product->min_lead_days }} ngày</span>
                                    @else
                                        <span class="text-muted">1 ngày</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->stock > 0)
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-danger">Hết</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->featured)
                                        <span class="badge bg-warning text-dark"><i class="bi bi-star-fill"></i> Nổi bật</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa bánh này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Chưa có bánh nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
