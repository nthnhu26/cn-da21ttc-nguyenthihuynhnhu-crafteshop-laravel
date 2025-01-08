@extends('home.layouts.app')
@section('title', 'Trang chủ')

@section('content')
<main>
    <!-- Banner Section -->
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $index => $banner)
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}"
                class="{{ $index == 0 ? 'active' : '' }}"
                aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                aria-label="Slide {{ $index + 1 }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($banners as $index => $banner)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="banner-text">
                                <h1>{{ $banner->title }}</h1>
                                <p>{{ $banner->description ?? 'Mô tả không có sẵn.' }}</p>
                                @if($banner->link)
                                <a href="{{ $banner->link }}" class="btn btn-primary">Xem sản phẩm</a>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="banner-image-wrapper">
                                <img src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @if($banners->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        @endif
    </div>

    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Danh mục nổi bật</h2>
            <div class="row g-4">
                <div class="col-md-4 text-center">
                    <div class="category-icon"><i class="fas fa-paint-brush"></i></div>
                    <h3>Tranh thủ công</h3>
                    <p>Khám phá các tác phẩm nghệ thuật độc đáo</p>
                    <a href="#" class="btn btn-outline-primary">Xem thêm</a>
                </div>
                <div class="col-md-4 text-center">

                    <div class="category-icon"><i class="fas fa-mug-hot"></i></div>
                    <h3>Gốm sứ</h3>
                    <p>Bộ sưu tập gốm sứ tinh tế và sang trọng</p>
                    <a href="#" class="btn btn-outline-primary">Xem thêm</a>
                </div>
                <div class="col-md-4 text-center">
                    <div class="category-icon"><i class="fas fa-gem"></i></div>
                    <h3>Trang sức thủ công</h3>
                    <p>Phụ kiện độc đáo cho phong cách của bạn</p>
                    <a href="#" class="btn btn-outline-primary">Xem thêm</a>
                </div>
            </div>
        </div>
    </section>

    <section class="featured-products py-5">
        <div class="container">
            <h2 class="text-center mb-4">Sản phẩm nổi bật</h2>
            <div class="product-carousel">
                @foreach($featuredProducts as $product)
                <div class="product-item">
                    <div class="product-card">
                        <div class="product-image">
                            @if($product->images->isNotEmpty())
                            <img data-src="{{ Storage::url($product->images->first()->image_url) }}"
                                alt="{{ $product->product_name }}"
                                class="img-fluid lazy-load">
                            @else
                            <img src="https://placehold.co/400x300"
                                alt="{{ $product->product_name }}"
                                class="img-fluid">
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-title">{{ $product->product_name }}</h3>
                            <p class="product-description">{{ $product->short_description }}</p>
                            <div class="product-price mb-2">
                                @if($product->getDiscountedPriceAttribute() < $product->price)
                                    <span class="original-price text-muted text-decoration-line-through">
                                        {{ number_format($product->price) }}đ
                                    </span>
                                    <span class="discounted-price text-danger ms-2">
                                        {{ number_format($product->getDiscountedPriceAttribute()) }}đ
                                    </span>
                                    @else
                                    <span class="price">
                                        {{ number_format($product->price) }}đ
                                    </span>
                                    @endif
                            </div>
                            <a href="{{ route('products.show', $product->product_id) }}"
                                class="btn btn-outline-primary">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4">Câu chuyện của chúng tôi</h2>
            <p class="lead mb-4">
                Tại Craftyzen, chúng tôi tôn vinh vẻ đẹp của những sản phẩm thủ công. Bộ sưu tập được tuyển chọn kỹ lưỡng của chúng tôi
                thể hiện tài năng của các nghệ nhân lành nghề, mang đến những sản phẩm độc đáo và bền vững cho ngôi nhà của bạn.
            </p>
            <p class="lead mb-4">
                Chúng tôi tự hào mang đến những sản phẩm thủ công mỹ nghệ độc đáo,
                kết hợp giữa truyền thống và hiện đại. Mỗi sản phẩm là một tác phẩm nghệ thuật,
                được chế tác tỉ mỉ bởi những nghệ nhân tài hoa.
            </p>
            <a href="#" class="btn btn-outline-primary">Tìm hiểu thêm về chúng tôi</a>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Khách hàng nói gì về chúng tôi</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card testimonial-card">
                        <div class="card-body">
                            <p class="card-text">"Sản phẩm thủ công tuyệt đẹp và chất lượng. Tôi rất hài lòng với trải nghiệm mua sắm của mình!"</p>
                            <p class="card-text"><small class="text-muted">- Nguyễn Văn A</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card">
                        <div class="card-body">
                            <p class="card-text">"Dịch vụ khách hàng tuyệt vời và giao hàng nhanh chóng. Chắc chắn sẽ mua lại!"</p>
                            <p class="card-text"><small class="text-muted">- Trần Thị B</small></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card">
                        <div class="card-body">
                            <p class="card-text">"Những sản phẩm thủ công độc đáo và ý nghĩa. Tôi đã tìm thấy món quà hoàn hảo cho bạn bè!"</p>
                            <p class="card-text"><small class="text-muted">- Lê Văn C</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">Đăng ký nhận bản tin</h2>
            <form class="row justify-content-center">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" placeholder="Nhập email của bạn" aria-label="Email của bạn">
                        <button class="btn btn-outline-primary" type="submit">Đăng ký</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection