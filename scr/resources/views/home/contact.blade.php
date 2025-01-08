@extends('home.layouts.app')
@section('title', 'Liên hệ')
@section('content')
<main class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="container-fluid mt-5">
                <x-page-header
                    title="Liên Hệ Với Chúng Tôi"
                    :breadcrumbs="[['name' => 'Liên hệ', 'url' => route('contact')]]" />
            </div>
            <section>
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <i class="bi bi-clock fs-1 text-primary mb-3 custom-icon"></i>
                                <h4>Thời gian làm việc</h4>
                                <p>9h00 - 18h00</p>
                                <p>
                                    Giờ làm việc của chúng tôi từ 09:00 đến 18:00 mỗi ngày. Trừ thứ 7 và chủ nhật.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <i class="bi bi-telephone fs-1 text-primary mb-3 custom-icon"></i>
                                <h4>Hotline</h4>
                                <p>(028) 1234 5678</p>
                                <p>
                                    Liên hệ trực tiếp qua số điện thoại hoặc qua Zalo để được tư vấn chi tiết nhẩt
                                </p>

                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <i class="bi bi-envelope fs-1 text-primary mb-3 custom-icon"></i>
                                <h4>Email</h4>
                                <p>craftyzen@gmail.com</p>
                                <p>Chúng tôi luôn theo dõi hộp thư đến và sẵn sàng phản hồi cho bạn sớm nhất.</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="text-center">
                                <i class="bi bi-map fs-1 text-primary mb-3 custom-icon"></i>
                                <h4>Địa chỉ</h4>
                                <p>Số 26, Phường 6, TP. Trà Vinh</p>
                                <p>Xem trên google map</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <style>
            .equal-height {
                height: 500px;
                /* Điều chỉnh kích thước theo nhu cầu */
                object-fit: cover;
            }
        </style>

        <div class="row">
            <div class="col-md-6 mb-md-0 form-border">
                <h1 class="text-center">Craftyzen sẵn sàng hỗ trợ</h1>
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <form action="{{ route('contact.submit') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="tel" class="form-control" id="phone" name="phone">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Nội dung tin nhắn</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                </form>
            </div>
            <div class="col-md-6 mb-md-0">
                <!-- Lottie Animation -->
                <lottie-player
                    src="{{ asset('assets/contact.json') }}"
                    background="transparent"
                    speed="1"
                    style="width: 100%; height: 500px;"
                    loop
                    autoplay>
                </lottie-player>
            </div>

        </div>
    </div>
</main>
@endsection