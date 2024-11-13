{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<main class="py-5">
    <div class="container">
        <h1 class="text-center mb-5 mt-4">Liên hệ với chúng tôi</h1>
        <div class="row">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="mb-4">Gửi tin nhắn cho chúng tôi</h2>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Nội dung tin nhắn</label>
                        <textarea class="form-control" id="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                </form>
            </div>
            <div class="col-md-6">
                <h2 class="mb-4">Thông tin liên hệ</h2>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="bi bi-geo-alt-fill text-primary me-2"></i>
                        Địa chỉ: 26, Phường 6, TP. Trà Vinh
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-telephone-fill text-primary me-2"></i>
                        (028) 1234 5678
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-envelope-fill text-primary me-2"></i>
                        info@dothucongmynghe.com
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-clock-fill text-primary me-2"></i>
                        Thứ Hai - Thứ Sáu: 9:00 - 18:00<br>
                        Thứ Bảy: 9:00 - 12:00<br>
                        Chủ Nhật: Đóng cửa
                    </li>
                </ul>
                
                <h3 class="mt-4 mb-3">Bản đồ</h3>
                <div class="ratio ratio-16x9">
    <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.0966108769426!2d106.34098753772432!3d9.929614967030545!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2z9.9296N,106.3410E!5e0!3m2!1svi!2s!4v1620123456789!5m2!1svi!2s"
        width="600" 
        height="450" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy">
    </iframe>
</div>

            </div>
        </div>
    </div>
</main>
@endsection