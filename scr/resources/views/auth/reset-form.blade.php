@extends('auth.layouts')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<h1 class="text-center">Đặt lại mật khẩu</h1>
<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
            id="email" name="email" placeholder="Nhập email của bạn" required
            value="{{ old('email', $email ?? '') }}" readonly>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Mật khẩu mới</label>
        <div class="input-group">
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                id="password" name="password" placeholder="Nhập mật khẩu mới" required>
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
    <button type="submit" class="btn btn-primary w-100 mb-3">Đặt lại mật khẩu</button>
</form>
@endsection