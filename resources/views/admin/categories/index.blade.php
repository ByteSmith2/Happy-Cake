@extends('admin.layouts.app')

@section('title', 'Quản lý danh mục bánh')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Danh sách danh mục bánh</h4>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Thêm danh mục
        </a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên danh mục</th>
                            <th>Slug</th>
                            <th>Số loại bánh</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>
                                    <span class="badge bg-secondary">{{ $category->products_count ?? $category->products->count() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
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
                                <td colspan="5" class="text-center text-muted">Chưa có danh mục nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
