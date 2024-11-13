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
        <h1 class="mb-4 mt-5">Chi tiết sản phẩm</h1>
        <nav aria-label="breadcrumb mt-5">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="#">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">Bình gốm hoa văn</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="d-block w-100" alt="Bình gốm hoa văn - Hình 1">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="d-block w-100" alt="Bình gốm hoa văn - Hình 2">
                        </div>
                        <div class="carousel-item">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="d-block w-100" alt="Bình gốm hoa văn - Hình 3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Trước</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sau</span>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <h1 class="mb-3">Bình gốm hoa văn</h1>
                <p class="fs-4 text-primary fw-bold mb-3">500.000 ₫</p>
                <p class="mb-4">Bình gốm thủ công với hoa văn truyền thống, phù hợp trang trí nội thất. Sản phẩm được làm thủ công bởi các nghệ nhân lành nghề, mang đậm bản sắc văn hóa Việt Nam.</p>
                <div class="mb-4">
                    <h5>Đặc điểm sản phẩm:</h5>
                    <ul>
                        <li>Chất liệu: Gốm sứ cao cấp</li>
                        <li>Kích thước: Cao 30cm, đường kính 15cm</li>
                        <li>Màu sắc: Xanh cobalt truyền thống</li>
                        <li>Hoa văn: Hoa sen và lá trúc</li>
                    </ul>
                </div>
                <form>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Số lượng:</label>
                        <input type="number" class="form-control" id="quantity" value="1" min="1" max="10">
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg mb-3">Thêm vào giỏ hàng</button>
                </form>
                <div class="d-flex align-items-center mb-4">
                    <i class="bi bi-truck me-2"></i>
                    <span>Miễn phí vận chuyển cho đơn hàng trên 1.000.000 ₫</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-shield-check me-2"></i>
                    <span>Bảo hành 12 tháng</span>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Mô tả sản phẩm</h2>
                <p>Bình gốm hoa văn là một tác phẩm nghệ thuật thủ công tinh tế, kết hợp giữa truyền thống và hiện đại. Được chế tác bởi những nghệ nhân lành nghề với bề dày kinh nghiệm, mỗi chiếc bình là một sản phẩm độc đáo, mang đậm bản sắc văn hóa Việt Nam.</p>
                <p>Với chất liệu gốm sứ cao cấp, bình được nung ở nhiệt độ cao, tạo nên độ bền và vẻ đẹp lâu dài. Hoa văn hoa sen và lá trúc được vẽ tay tỉ mỉ, tượng trưng cho sự tinh khiết và sức sống mãnh liệt của thiên nhiên Việt Nam.</p>
                <p>Màu xanh cobalt truyền thống không chỉ tạo nên vẻ đẹp cổ điển mà còn mang lại cảm giác yên bình, tĩnh lặng cho không gian sống. Bình gốm này không chỉ là một vật trang trí, mà còn là một tác phẩm nghệ thuật, một món quà ý nghĩa cho người thân và bạn bè.</p>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Đánh giá sản phẩm</h2>
                <form action="" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="rating">Đánh giá:</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" required><label for="star5" title="5 stars"></label>
                            <input type="radio" id="star4" name="rating" value="4" required><label for="star4" title="4 stars"></label>
                            <input type="radio" id="star3" name="rating" value="3" required><label for="star3" title="3 stars"></label>
                            <input type="radio" id="star2" name="rating" value="2" required><label for="star2" title="2 stars"></label>
                            <input type="radio" id="star1" name="rating" value="1" required><label for="star1" title="1 star"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">Nhận xét:</label>
                        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Gửi đánh giá</button>
                </form>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="mb-4">Sản phẩm liên quan</h2>
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Đĩa gốm hoa văn">
                            <div class="card-body">
                                <h5 class="card-title">Đĩa gốm hoa văn</h5>
                                <p class="card-text fw-bold">300.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Bộ ấm chén gốm">
                            <div class="card-body">
                                <h5 class="card-title">Bộ ấm chén gốm</h5>
                                <p class="card-text fw-bold">450.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Lọ hoa gốm nhỏ">
                            <div class="card-body">
                                <h5 class="card-title">Lọ hoa gốm nhỏ</h5>
                                <p class="card-text fw-bold">250.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="{{ URL::asset('images/default.jpg') }}" class="card-img-top" alt="Tượng gốm trang trí">
                            <div class="card-body">
                                <h5 class="card-title">Tượng gốm trang trí</h5>
                                <p class="card-text fw-bold">400.000 ₫</p>
                                <a href="#" class="btn btn-outline-primary">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection