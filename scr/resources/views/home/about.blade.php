@extends('home.layouts.app')
@section('title', 'Về chúng tôi')
@section('content')
<main class="py-5 mt-5">
    <section class="py-5 bg-light">
        <x-page-header
            title="Giới Thiệu"
            :breadcrumbs="[['name' => 'Giới thiệu', 'url' => route('about')]]" />
    </section>
    <section class="bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="assets/images/about.png" alt="Hình ảnh giới thiệu" class="img-fluid rounded shadow">
                </div>
                <div class="col-md-6">
                    <h2 class="mb-4">Câu chuyện của chúng tôi</h2>
                    <p>Craftyzen được thành lập vào năm 2024 với mục tiêu bảo tồn và phát triển nghề thủ công truyền
                        thống Việt Nam. Chúng tôi bắt đầu từ một cửa hàng nhỏ tại Trà Vinh với mong muốn sẽ trở
                        thành một trong những thương hiệu hàng đầu trong lĩnh vực đồ thủ công mỹ nghệ.</p>
                    <p>Chúng tôi tự hào mang đến cho khách hàng những sản phẩm thủ công tinh xảo, độc đáo và mang
                        đậm bản sắc văn hóa Việt Nam. Mỗi sản phẩm của chúng tôi đều là kết tinh của sự sáng tạo, kỹ
                        năng và tâm huyết của các nghệ nhân lành nghề.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Sứ mệnh và Tầm nhìn</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Sứ mệnh</h3>
                            <p class="card-text">Sứ mệnh của chúng tôi là bảo tồn và phát triển các nghề thủ công
                                truyền thống Việt Nam, đồng thời tạo ra cơ hội việc làm và thu nhập ổn định cho các
                                nghệ nhân địa phương. Chúng tôi cam kết mang đến cho khách hàng những sản phẩm chất
                                lượng cao, độc đáo và thân thiện với môi trường.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Tầm nhìn</h3>
                            <p class="card-text">Chúng tôi hướng tới việc trở thành thương hiệu hàng đầu trong lĩnh
                                vực đồ thủ công mỹ nghệ, không chỉ tại Việt Nam mà còn trên thị trường quốc tế.
                                Chúng tôi mong muốn góp phần quảng bá văn hóa và nghệ thuật truyền thống Việt Nam ra
                                toàn thế giới, đồng thời thúc đẩy sự phát triển bền vững của cộng đồng nghệ nhân địa
                                phương.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Quy trình sản xuất</h2>
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-pencil-square fs-1 text-primary mb-3 custom-icon"></i>
                        <h4>Thiết kế</h4>
                        <p>Lên ý tưởng và phác thảo sản phẩm</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-tools fs-1 text-primary mb-3 custom-icon"></i>
                        <h4>Chế tác</h4>
                        <p>Tạo ra sản phẩm bằng kỹ thuật thủ công</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-check-circle fs-1 text-primary mb-3 custom-icon"></i>
                        <h4>Kiểm tra chất lượng</h4>
                        <p>Đảm bảo sản phẩm đạt tiêu chuẩn cao nhất</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="text-center">
                        <i class="bi bi-box-seam fs-1 text-primary mb-3 custom-icon"></i>
                        <h4>Đóng gói và giao hàng</h4>
                        <p>Cẩn thận đóng gói và gửi đến tay khách hàng</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container text-center">
            <h2 class="mb-4">Khám phá bộ sưu tập của chúng tôi</h2>
            <p class="lead mb-4">Hãy trải nghiệm vẻ đẹp của nghệ thuật thủ công Việt Nam qua các sản phẩm độc đáo
                của chúng tôi</p>
            <a href="{{route('products.index')}}" class="btn btn-outline-primary">Xem sản phẩm</a>
        </div>
    </section>
</main>
@endsection