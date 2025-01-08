@extends('auth.layouts')

@section('title', 'Đăng nhập')
@section('content')
<h1 class="text-center">Đăng nhập</h1>
<a href="{{ route('home') }}" class="close-btn text-decoration-none">&times;</a>

<!-- Form đăng nhập -->
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="mb-3">
        <a href="{{ route('google.redirect') }}" class="btn btn-google w-100">
            <img src="assets/images/google.svg" alt="Google Logo" class="me-2">
            Đăng nhập bằng Google
        </a>
    </div>
    <div class="text-center mt-3">
        <div class="d-flex align-items-center">
            <hr class="flex-fill">
            <span class="mx-2">Hoặc</span>
            <hr class="flex-fill">
        </div>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <div class="input-group">
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Nhập email của bạn" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu</label>
        <div class="input-group">
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Nhập mật khẩu của bạn" required>
            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                <i class="bi bi-eye-slash" id="toggleIconPassword"></i>
            </span>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
    </div>
    <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
</form>

<div class="text-center mt-3">
    <a href="{{ route('password.request') }}" class="text-decoration-none text-muted">Quên mật khẩu? Gửi email đặt lại mật khẩu</a>
</div>
<div class="text-center mt-3 mb-3">
    <a href="{{ route('register') }}" class="text-decoration-none text-muted">Tạo tài khoản mới</a>
</div>



@endsection