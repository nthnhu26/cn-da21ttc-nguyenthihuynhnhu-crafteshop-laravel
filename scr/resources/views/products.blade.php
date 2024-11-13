{{--resources/views/products.blade.php--}}
@extends('layouts.app')
@section('title','Sản phẩm')
@section('extra_css')
<style>
    img.card-img-top {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
</style>
@endsection
@section('content')
<main class="py-5">
    <div class="container">
        <h1 class="mb-4 mt-5">Sản phẩm thủ công mỹ nghệ</h1>
        <div class="row mb-4">
            <div class="col-md-4 mb-3 mb-md-0">
                <select class="form-select" aria-label="Lọc theo danh mục">
                    <option selected>Tất cả danh mục</option>
                    <option value="1">Gốm sứ</option>
                    <option value="2">Đồ gỗ</option>
                    <option value="3">Thêu ren</option>
                    <option value="4">Trang sức</option>
                </select>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <select class="form-select" aria-label="Sắp xếp theo">
                    <option selected>Sắp xếp theo</option>
                    <option value="1">Giá: Thấp đến cao</option>
                    <option value="2">Giá: Cao đến thấp</option>
                    <option value="3">Mới nhất</option>
                    <option value="4">Bán chạy nhất</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm" aria-label="Tìm kiếm sản phẩm">
                    <button class="btn btn-outline-secondary" type="button"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Bình gốm hoa văn">
                    <div class="card-body">
                        <h5 class="card-title">Bình gốm hoa văn</h5>
                        <p class="card-text">Bình gốm thủ công với hoa văn truyền thống, phù hợp trang trí nội thất.</p>
                        <p class="fw-bold">500.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Khay gỗ chạm khắc">
                    <div class="card-body">
                        <h5 class="card-title">Khay gỗ chạm khắc</h5>
                        <p class="card-text">Khay gỗ thủ công với hoa văn chạm khắc tinh xảo, đa năng.</p>
                        <p class="fw-bold">750.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Khăn thêu tay">
                    <div class="card-body">
                        <h5 class="card-title">Khăn thêu tay</h5>
                        <p class="card-text">Khăn lụa thêu tay với họa tiết hoa sen truyền thống Việt Nam.</p>
                        <p class="fw-bold">300.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Vòng tay đá quý">
                    <div class="card-body">
                        <h5 class="card-title">Vòng tay đá quý</h5>
                        <p class="card-text">Vòng tay thủ công từ đá quý tự nhiên, mang lại may mắn.</p>
                        <p class="fw-bold">450.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Tranh thêu phong cảnh">
                    <div class="card-body">
                        <h5 class="card-title">Tranh thêu phong cảnh</h5>
                        <p class="card-text">Tranh thêu tay phong cảnh Việt Nam, tỉ mỉ và sống động.</p>
                        <p class="fw-bold">1.200.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Nón lá thêu">
                    <div class="card-body">
                        <h5 class="card-title">Nón lá thêu</h5>
                        <p class="card-text">Nón lá truyền thống với hoa văn thêu tay độc đáo.</p>
                        <p class="fw-bold">200.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Túi xách mây tre đan">
                    <div class="card-body">
                        <h5 class="card-title">Túi xách mây tre đan</h5>
                        <p class="card-text">Túi xách thủ công từ mây tre đan, thời trang và bền bỉ.</p>
                        <p class="fw-bold">350.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100">
                    <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Đèn lồng giấy dó">
                    <div class="card-body">
                        <h5 class="card-title">Đèn lồng giấy dó</h5>
                        <p class="card-text">Đèn lồng thủ công từ giấy dó, trang trí nội thất độc đáo.</p>
                        <p class="fw-bold">280.000 ₫</p>
                        <a href="#" class="btn btn-primary w-100">Thêm vào giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>

        <nav aria-label="Phân trang" class="my-5">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Trước</a>
                </li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Sau</a>
                </li>
            </ul>
        </nav>
    </div>
    </main>
@endsection