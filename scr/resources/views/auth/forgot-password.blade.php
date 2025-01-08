@extends('auth.layouts')

@section('title', 'Quên mật khẩu')

@section('content')
<h1 class="text-center">Quên mật khẩu</h1>
<a href="{{ route('home') }}" class="close-btn text-decoration-none">&times;</a>
<div class="text-center">
    <span>Nhập email của bạn bên dưới, chúng tôi sẽ gửi liên kết đặt lại mật khẩu.</span>
</div>
<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
            id="email" name="email" placeholder="Nhập email của bạn" required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100">Gửi liên kết đặt lại mật khẩu</button>
</form>
<div class="text-center mt-3 mb-3">
    <a href="{{ route('login') }}" class="text-decoration-none">Đã có tài khoản? Đăng nhập ngay</a>
</div>
@endsection