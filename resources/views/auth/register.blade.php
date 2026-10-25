@extends('layouts.app')

@section('title', 'Đăng Ký Tài Khoản - MangaStream')

@section('content')
<div class="container" style="max-width: 480px;">

    <div class="card card-custom p-4 p-md-5 my-4 shadow-lg">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-light mb-1">Tạo Tài Khoản</h3>
            <p class="text-secondary small">Đăng ký thành viên để nhận ngay <strong>50 Xu</strong> miễn phí!</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label text-light small fw-semibold">Họ và Tên</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="name" class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus placeholder="Nguyễn Văn A">
                </div>
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-light small fw-semibold">Địa chỉ Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="name@example.com">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-light small fw-semibold">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror" required placeholder="Ít nhất 6 ký tự">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label text-light small fw-semibold">Xác nhận mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-lock-open"></i></span>
                    <input type="password" name="password_confirmation" class="form-control bg-dark text-light border-secondary" required placeholder="Nhập lại mật khẩu">
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold mb-3">
                <i class="fa-solid fa-user-plus me-2"></i> Đăng Ký Tài Khoản
            </button>
        </form>

        <div class="text-center small text-secondary">
            Đã có tài khoản? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Đăng nhập</a>
        </div>
    </div>

</div>
@endsection
