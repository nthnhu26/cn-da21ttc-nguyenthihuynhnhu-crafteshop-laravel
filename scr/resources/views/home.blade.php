@extends('layouts.app')

@section('title', 'Trang chủ')

@section('extra_css')
<style>
    .banner {
        background-color: #007bff; /* Màu tạm thời thay cho hình ảnh */
        height: 400px;
    }
    img {
        width: 100%;
        height: 200px;
    }
</style>
@endsection

@section('content')

    <div class="banner d-flex align-items-center justify-content-center text-white">
        <div class="text-center">
            <h1 class="display-4">Đồ Thủ Công Mỹ Nghệ Việt Nam</h1>
            <p class="lead">Khám phá vẻ đẹp tinh tế của nghề thủ công truyền thống</p>
            <a href="{{ route('products') }}" class="btn btn-light btn-lg">Xem sản phẩm</a>
        </div>
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto text-center">
                    <h2 class="mb-4">Về chúng tôi</h2>
                    <p class="lead mb-4">
                        Chúng tôi tự hào mang đến những sản phẩm thủ công mỹ nghệ độc đáo, 
                        kết hợp giữa truyền thống và hiện đại. Mỗi sản phẩm là một tác phẩm nghệ thuật, 
                        được chế tác tỉ mỉ bởi những nghệ nhân tài hoa.
                    </p>
                    <a href="{{ route('about') }}" class="btn btn-outline-primary">Tìm hiểu thêm</a>
                </div>
            </div>
        </div>
    </section>
    <section class="py-5">
            <div class="container">
                <h2 class="text-center mb-5">Sản phẩm nổi bật</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Sản phẩm 1">
                            <div class="card-body">
                                <h5 class="card-title">Sản phẩm thủ công 1</h5>
                                <p class="card-text">Giá: 500.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary w-100">Thêm vào giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Sản phẩm 2">
                            <div class="card-body">
                                <h5 class="card-title">Sản phẩm thủ công 2</h5>
                                <p class="card-text">Giá: 750.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary w-100">Thêm vào giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Sản phẩm 3">
                            <div class="card-body">
                                <h5 class="card-title">Sản phẩm thủ công 3</h5>
                                <p class="card-text">Giá: 600.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary w-100">Thêm vào giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Sản phẩm 4">
                            <div class="card-body">
                                <h5 class="card-title">Sản phẩm thủ công 4</h5>
                                <p class="card-text">Giá: 850.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary w-100">Thêm vào giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-primary text-white py-5">
            <div class="container text-center">
                <h2 class="mb-4">Câu chuyện của chúng tôi</h2>
                <p class="lead mb-4">
                    Tại Craftyzen, chúng tôi tôn vinh vẻ đẹp của những sản phẩm thủ công. Bộ sưu tập được tuyển chọn kỹ lưỡng của chúng tôi
                    thể hiện tài năng của các nghệ nhân lành nghề, mang đến những sản phẩm độc đáo và bền vững cho ngôi nhà của bạn.
                </p>
                <a href="#" class="btn btn-light btn-lg">Tìm hiểu thêm về chúng tôi</a>
            </div>
        </section>

        <section class="py-5">
            <div class="container text-center">
                <h2 class="mb-4">Đăng ký nhận bản tin</h2>
                <form class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <input type="email" class="form-control" placeholder="Nhập email của bạn" aria-label="Email của bạn">
                            <button class="btn btn-primary" type="submit">Đăng ký</button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
@endsection
