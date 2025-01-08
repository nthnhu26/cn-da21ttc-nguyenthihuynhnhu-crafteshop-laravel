@extends('home.layouts.app')
@section('title', 'Sản phẩm')
@section('content')
<div class="container-fluid py-4 px-5">
    <div class="row">
        <!-- Sidebar Danh Mục & Bộ Lọc -->
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card category-sidebar">
                <div class="card-body">
                    <!-- Danh mục -->
                    <h5 class="mb-3">
                        <i class="bi bi-list-task me-2"></i>Danh Mục
                    </h5>
                    <div class="nav flex-column mb-4">
                        @foreach($categories as $category)
                        <a href="#category-{{ $category->category_id }}" class="nav-link text-dark category-link">
                            {{ $category->category_name }}
                        </a>
                        @endforeach
                    </div>
                    <!-- Bộ Lọc -->
                    <h5 class="mb-3">
                        <i class="bi bi-funnel me-2"></i>Bộ Lọc
                    </h5>
                    <form id="filterForm" class="filter-form">
                        <!-- Lọc Đánh Giá -->
                        <div class="mb-4">
                            <h6>Đánh giá</h6>
                            @for($i = 5; $i >= 1; $i--)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}">
                                <label class="form-check-label" for="rating{{ $i }}">
                                    @for($j = 1; $j <= $i; $j++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                        @endfor
                                </label>
                            </div>
                            @endfor
                        </div>
                        <!-- Lọc Khoảng Giá -->
                        <div class="mb-4">
                            <h6>Khoảng giá</h6>
                            <select class="form-select" name="price_range">
                                <option value="">Chọn khoảng giá</option>
                                <option value="0-100000">Dưới 100,000đ</option>
                                <option value="100000-200000">100,000đ - 200,000đ</option>
                                <option value="200000-500000">200,000đ - 500,000đ</option>
                                <option value="500000-1000000">500,000đ - 1,000,000đ</option>
                                <option value="1000000+">Trên 1,000,000đ</option>
                            </select>
                        </div>
                        <!-- Nút Lọc -->
                        <button type="submit" class="btn btn-primary w-100 mb-3">
                            <i class="bi bi-search me-2"></i>Lọc
                        </button>
                        <!-- Nút Reset -->
                        <button type="reset" class="btn btn-outline-secondary w-100" id="resetFilter">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset Bộ Lọc
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Nội Dung Sản Phẩm -->
        <div class="col-md-8 col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="mb-4">Tất cả sản phẩm</h2>
                    <!-- Danh mục sản phẩm -->
                    @foreach($categories as $category)
                    <div id="category-{{ $category->category_id }}" class="product-category mb-5">
                        <h3 class="mb-3">{{ $category->category_name }}</h3>
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 product-container">
                            @php
                            $categoryProducts = $products->where('category_id', $category->category_id);
                            @endphp
                            @foreach($categoryProducts->take(4) as $product)
                            <div class="col product-item">
                                <div class="card h-100 position-relative">
                                    <div class="position-relative">
                                        {{-- Hiển thị hình ảnh chính --}}
                                        @php
                                        $primaryImage = $product->images->where('is_primary', true)->first();
                                        @endphp

                                        @if($primaryImage)
                                        <img src="{{ Storage::url($primaryImage->image_url) }}" alt="{{ $product->name }}" style="max-width: 200px; max-height: 200px;">
                                        @else
                                        <img src="https://via.placeholder.com/200" alt="{{ $product->name }}" style="max-width: 200px; max-height: 200px;">
                                        @endif

                                        @if($product->price != $product->discounted_price)
                                        <div class="badge bg-danger position-absolute top-0 end-0 m-2">
                                            -{{ round((1 - $product->discounted_price / $product->price) * 100) }}%
                                        </div>
                                        @endif
                                        <!-- Hover icons -->
                                        <div class="hover-icons position-absolute w-100 h-100 top-0 start-0 d-flex justify-content-center align-items-center gap-2 invisible">
                                            <a href="{{ route('products.show', ['id' => $product->product_id]) }}" class="btn btn-outline-light">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <button class="btn btn-outline-light add-to-cart" data-product-id="{{ $product->product_id }}">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                            <a href="#" class="btn btn-outline-light">
                                                <i class="bi bi-bag"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $product->product_name }}</h5>
                                        <p class="card-text text-muted small">{{ $product->short_description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="bi {{ $i <= round($product->average_rating) ? 'bi-star-fill text-warning' : 'bi-star text-muted' }}"></i>
                                                    @endfor

                                                    <!-- Giá khuyến mãi -->
                                                    @if ($product->price != $product->discounted_price)
                                                    <span class="text-danger fw-bold">
                                                        {{ number_format($product->discounted_price, 0, ',', '.') }} đ
                                                    </span>
                                                    <span class="text-muted text-decoration-line-through small">
                                                        {{ number_format($product->price, 0, ',', '.') }} đ
                                                    </span>
                                                    @else
                                                    <!-- Không có khuyến mãi -->
                                                    <span class="text-dark fw-bold">
                                                        {{ number_format($product->price, 0, ',', '.') }} đ
                                                    </span>
                                                    @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <!-- Nút Xem Thêm và Thu Gọn -->
                        <div class="text-center mt-4">
                            @if($categoryProducts->count() > 4)
                            <button class="btn btn-outline-primary view-more" data-category="{{ $category->category_id }}">Xem thêm sản phẩm</button>
                            <button class="btn btn-outline-secondary d-none collapse-products" data-category="{{ $category->category_id }}">Thu gọn</button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX Script -->
<script>
    $(document).ready(function() {
        // Hiệu ứng hover
        $('.product-item').hover(
            function() {
                $(this).find('.hover-icons').removeClass('invisible');
            },
            function() {
                $(this).find('.hover-icons').addClass('invisible');
            }
        );

        // Xem thêm sản phẩm
        $('.view-more').on('click', function() {
            const categoryId = $(this).data('category');
            const button = $(this);
            const collapseButton = $(`.collapse-products[data-category="${categoryId}"]`);

            button.prop('disabled', true).text('Đang tải...');

            $.ajax({
                url: "{{ route('products.index') }}",
                type: "GET",
                data: {
                    category_id: categoryId,
                    load_more: true
                },
                success: function(response) {
                    $(`#category-${categoryId} .product-container`).append(response.html);
                    button.addClass('d-none');
                    collapseButton.removeClass('d-none');
                },
                error: function() {
                    alert('Có lỗi xảy ra khi tải thêm sản phẩm.');
                    button.prop('disabled', false).text('Xem thêm sản phẩm');
                }
            });
        });

        // Thu gọn sản phẩm
        $('.collapse-products').on('click', function() {
            const categoryId = $(this).data('category');
            const viewMoreButton = $(`.view-more[data-category="${categoryId}"]`);
            const productContainer = $(`#category-${categoryId} .product-container`);

            productContainer.find('.col').slice(4).remove();
            $(this).addClass('d-none');
            viewMoreButton.removeClass('d-none');
        });

        // Reset bộ lọc
        $('#resetFilter').on('click', function() {
            $('#filterForm')[0].reset();
            location.href = "{{ route('products.index') }}";
        });
    });
</script>
@endsection