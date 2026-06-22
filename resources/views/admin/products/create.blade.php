@extends('admin.layouts.app')

@section('title', 'Thêm bánh mới')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="bi bi-cake2-fill me-2" style="color: #f4a261;"></i>Thêm bánh mới</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên bánh <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Giá gốc <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" min="0" required>
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price') }}" min="0">
                                        <span class="input-group-text">đ</span>
                                    </div>
                                    @error('sale_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Tồn kho <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả bánh</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NÂNG CAO: size variants --}}
                        <div class="card border-warning mb-3">
                            <div class="card-header bg-warning bg-opacity-25">
                                <strong><i class="bi bi-rulers me-1"></i>Tùy chọn size (nâng cao)</strong>
                                <small class="text-muted d-block">Nếu bánh có nhiều size khác nhau, điền dưới đây. Mỗi size 1 dòng. Để trống nếu bánh chỉ có 1 giá.</small>
                            </div>
                            <div class="card-body">
                                @php $oldSizes = old('sizes', [['label'=>'','price'=>''],['label'=>'','price'=>''],['label'=>'','price'=>'']]); @endphp
                                @for($i = 0; $i < 3; $i++)
                                    <div class="row g-2 mb-2 align-items-center">
                                        <div class="col-md-2">
                                            <strong>Size {{ $i + 1 }}:</strong>
                                        </div>
                                        <div class="col-md-7">
                                            <input type="text" name="sizes[{{ $i }}][label]"
                                                class="form-control"
                                                value="{{ $oldSizes[$i]['label'] ?? '' }}"
                                                placeholder="Ví dụ: Nhỏ (16cm - 4~6 người)">
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group">
                                                <input type="number" name="sizes[{{ $i }}][price]"
                                                    class="form-control"
                                                    value="{{ $oldSizes[$i]['price'] ?? '' }}"
                                                    min="0" placeholder="Giá">
                                                <span class="input-group-text">đ</span>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="image" class="form-label">Hình ảnh bánh</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- NÂNG CAO: min_lead_days --}}
                        <div class="mb-3">
                            <label for="min_lead_days" class="form-label">
                                <i class="bi bi-clock-fill me-1" style="color: #f4a261;"></i>
                                Đặt trước tối thiểu (ngày) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control @error('min_lead_days') is-invalid @enderror"
                                id="min_lead_days" name="min_lead_days"
                                value="{{ old('min_lead_days', 1) }}" min="1" max="14" required>
                            <small class="text-muted">1 = đặt trước 1 ngày. 3 = bánh sinh nhật custom. 4+ = bánh đặc biệt.</small>
                            @error('min_lead_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">
                                    <i class="bi bi-star-fill text-warning"></i> Bánh bán chạy (nổi bật)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Tạo bánh
                </button>
            </form>
        </div>
    </div>
@endsection
