<footer class="bg-light py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Liên hệ</h5>
                <p><i class="bi bi-geo-alt-fill me-2"></i> Địa chỉ: 26, Phường 6, TP. Trà Vinh</p>
                <p><i class="bi bi-envelope-fill me-2"></i> Email: info@dothucongmynghe.com</p>
                <p><i class="bi bi-telephone-fill me-2"></i> Điện thoại: (028) 1234 5678</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Liên kết nhanh</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('return-policy') }}">Chính sách đổi trả</a></li>
                    <li><a href="{{ route('buying-guide') }}">Hướng dẫn mua hàng</a></li>
                    <li><a href="{{ route('faq') }}">Câu hỏi thường gặp</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5>Kết nối với chúng tôi</h5>
                <div class="d-flex justify-content-start">
                    <a href="#" class="text-decoration-none me-3 social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-decoration-none me-3 social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-decoration-none social-icon"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
        </div>
        <hr />
        <div class="text-center">
            <p>&copy; {{ date('Y') }} Đồ Thủ Công Mỹ Nghệ. Bảo lưu mọi quyền.</p>
        </div>
    </div>
</footer>
