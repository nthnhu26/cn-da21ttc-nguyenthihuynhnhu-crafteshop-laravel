
@extends('emails.layout')

@section('title', 'Đặt lại mật khẩu')

@section('content')
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #28a745;">Đặt lại mật khẩu</h2>
    </div>

    <div style="margin-bottom: 20px;">
        <p><strong>Nhấp vào liên kết bên dưới để đặt lại mật khẩu của bạn:</p>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ url('password/reset', $token) }}?email={{ urlencode($email) }}" style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">Đặt lại mật khẩu</a>
    </div>
</div>
@endsection