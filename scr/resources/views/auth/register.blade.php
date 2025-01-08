@extends('auth.layouts')

@section('title', 'Đăng ký')
@section('content')

<h1 class="text-center">Đăng ký</h1>
<a href="{{ route('home') }}" class="close-btn text-decoration-none">&times;</a>
<form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf
    <div class="mb-3">
        <a href="{{ route('google.redirect') }}" class="btn btn-google w-100">
            <img src="assets/images/google.svg" alt="Google Logo" class="me-2">
            Đăng ký bằng Google
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
        <label for="name" class="form-label">Tên</label>
        <div class="input-group">
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Nhập tên của bạn" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
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
    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Nhập lại mật khẩu</label>
        <div class="input-group">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
            <span class="input-group-text" id="togglePasswordConfirmation" style="cursor: pointer;">
                <i class="bi bi-eye-slash" id="toggleIconConfirmation"></i>
            </span>
        </div>
    </div>
    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="text-decoration-none text-muted">Đã có tài khoản? Đăng nhập ngay</a>
    </div>
</form>





@endsection