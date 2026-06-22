@extends('layouts.app')

@section('title', 'Tài khoản - Happy Cake')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Tài khoản của tôi</h3>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--accent);"></i>
                    <h5 class="mt-3">{{ auth()->user()->name }}</h5>
                    <p class="text-muted">{{ auth()->user()->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-accent btn-sm">Chỉnh sửa thông tin</a>
                </div>
            </div>
        </div>
        @if(auth()->user()->is_admin)
        <div class="col-md-4 mb-3">
            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                <div class="card text-center h-100 border-0 shadow-sm">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <i class="bi bi-speedometer2" style="font-size: 3rem; color: var(--accent);"></i>
                        <h5 class="mt-3">Trang quản trị</h5>
                        <p class="text-muted">Vào khu vực quản trị</p>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
