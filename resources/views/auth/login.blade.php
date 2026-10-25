@extends('layouts.app')

@section('title', 'Đăng Nhập Tài Khoản - MangaStream')

@section('content')
<div class="container" style="max-width: 480px;">

    <div class="card card-custom p-4 p-md-5 my-4 shadow-lg">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-light mb-1">Đăng Nhập</h3>
            <p class="text-secondary small">Chào mừng bạn trở lại với MangaStream</p>
        </div>

        <!-- Sample Accounts Quick Fill (Helpful for defense/demo) -->
        <div class="alert alert-dark border-secondary p-3 mb-4 small">
            <div class="fw-bold text-light mb-1"><i class="fa-solid fa-key text-warning me-1"></i> Tài khoản mẫu dùng để thử nghiệm:</div>
            <div class="text-secondary">• <strong>Admin:</strong> admin@mangastream.com / password123</div>
            <div class="text-secondary">• <strong>VIP Member:</strong> vip@mangastream.com / password123</div>
            <div class="text-secondary">• <strong>User thường:</strong> user@mangastream.com / password123</div>
        </div>

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label text-light small fw-semibold">Địa chỉ Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control bg-dark text-light border-secondary @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="name@example.com">
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label text-light small fw-semibold">Mật khẩu</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-secondary"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control bg-dark text-light border-secondary @error('password') is-invalid @enderror" required placeholder="••••••••">
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4 small">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label text-secondary" for="rememberMe">Ghi nhớ đăng nhập</label>
                </div>
                <a href="#" class="text-primary text-decoration-none">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold mb-3">
                <i class="fa-solid fa-right-to-bracket me-2"></i> Đăng Nhập
            </button>
        </form>

        <div class="text-center small text-secondary">
            Chưa có tài khoản? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Đăng ký ngay</a> (Nhận 50 Xu)
        </div>
    </div>

</div>
@endsection
