@extends('home.layouts.app')
@section('title', $product->product_name)
@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Image Gallery -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="product-image-gallery mb-3">
                        @php
                        $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                        @endphp

                        @if($primaryImage)
                        <img src="{{ Storage::url($primaryImage->image_url) }}" class="card-img-top" alt="{{ $product->product_name }}">
                        @else
                        <img src="https://placehold.co/400x300" class="card-img-top" alt="{{ $product->product_name }}">
                        @endif
                    </div>

                    <div class="product-thumbnail-container">
                        <div class="product-thumbnail-wrapper">
                            <div class="product-thumbnail-scroll" id="productThumbnailScroll">
                                @foreach($product->images as $index => $image)
                                <div class="product-thumbnail-item">
                                    <img src="{{ Storage::url($image->image_url) }}"
                                        data-src="{{ Storage::url($image->image_url) }}"
                                        class="product-small-img rounded"
                                        onclick="changeProductImage(this)"
                                        alt="Hình ảnh {{ $index + 1 }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <button class="product-thumbnail-nav product-thumbnail-prev" onclick="scrollProductThumbnails(-1)">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="product-thumbnail-nav product-thumbnail-next" onclick="scrollProductThumbnails(1)">
                            <i class="bi bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Information -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h2 mb-0">{{ $product->product_name }}</h1>
                        <span class="badge {{ $product->status === 'in_stock' ? 'bg-success' : 'bg-danger' }}">
                            <i class="bi {{ $product->status === 'in_stock' ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                            {{ $product->status === 'in_stock' ? 'Còn hàng' : 'Hết hàng' }}
                        </span>
                    </div>
                    <p class="text-muted">Mã sản phẩm: {{ $product->product_id }}</p>
                    <div class="d-flex flex-column align-items-start mb-4">
                        @if($product->price != $product->discounted_price)
                        <span class="badge bg-danger mb-1">
                            -{{ round((($product->price - $product->getDiscountedPriceAttribute()) / $product->price) * 100) }}%
                        </span>
                        <div class="d-flex align-items-center">
                            <h2 class="h5 text-muted mb-0 me-2" style="text-decoration: line-through;">
                                {{ number_format($product->price, 0, ',', '.') }} VNĐ
                            </h2>
                            <h2 class="h3 text-primary mb-0" id="productPrice">
                                {{ number_format($product->discounted_price, 0, ',', '.') }} VNĐ
                            </h2>
                        </div>
                        @else
                        <h2 class="h3 text-primary mb-0" id="productPrice">
                            {{ number_format($product->price, 0, ',', '.') }} VNĐ
                        </h2>
                        @endif
                    </div>

                    <div class="product-quantity mb-4">
                        <label class="mb-2">Số lượng:</label>
                        <div class="product-quantity-control">
                            <button class="product-quantity-btn" id="decreaseQuantity">-</button>
                            <input type="number" id="productQuantity" class="product-quantity-input" value="1" min="1">
                            <button class="product-quantity-btn" id="increaseQuantity">+</button>
                        </div>
                    </div>

                    <div class="product-action-buttons">
                        <button class="btn btn-primary" onclick="buyNow()">
                            <i class="bi bi-lightning-fill"></i> Mua ngay
                        </button>
                        <button class="btn btn-outline-primary" onclick="addToCart('{{ $product->product_id }}')">
                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                        </button>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details -->
    <div class="card mt-4">
        <div class="card-body">
            <h3 class="card-title">Thông tin chi tiết</h3>
            <p>{{ $product->description }}</p>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>Chất liệu:</strong> {{ $product->material }}</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>Kích thước:</strong> {{ $product->dimensions }}</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>Xuất xứ:</strong> {{ $product->origin }}</li>
                        <li class="mb-2"><i class="bi bi-check2-circle text-primary me-2"></i><strong>Bảo hành:</strong> {{ $product->warranty_period }} tháng</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="card-title mb-0">Đánh giá sản phẩm</h3>
                <div class="d-flex align-items-center">
                    <h2 class="h1 mb-0 me-3">{{ number_format($product->reviews->avg('rating'), 1) }}</h2>
                    <div>
                        <div class="fs-4 text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                @if($i <=floor($product->reviews->avg('rating')))
                                <i class="bi bi-star-fill"></i>
                                @elseif($i == ceil($product->reviews->avg('rating')) && $product->reviews->avg('rating') - floor($product->reviews->avg('rating')) >= 0.5)
                                <i class="bi bi-star-half"></i>
                                @else
                                <i class="bi bi-star"></i>
                                @endif
                                @endfor
                        </div>
                        <p class="mb-0 text-muted">{{ $product->reviews->count() }} đánh giá</p>
                    </div>
                </div>
            </div>

            <div class="btn-group mb-4" role="group" aria-label="Rating filter">
                <input type="radio" class="btn-check" name="rating-filter" id="allRatings" checked>
                <label class="btn btn-outline-primary" for="allRatings">
                    Tất cả ({{ $product->reviews->count() }})
                </label>

                @for ($i = 5; $i >= 1; $i--)
                @php $count = $product->reviews->where('rating', $i)->count(); @endphp
                <input type="radio" class="btn-check" name="rating-filter" id="rating{{ $i }}">
                <label class="btn btn-outline-primary" for="rating{{ $i }}">
                    {{ $i }} <i class="bi bi-star-fill text-warning"></i> ({{ $count }})
                </label>
                @endfor
            </div>

            <div class="product-review-list">
                @forelse ($product->reviews as $review)
                <div class="card mb-3 product-review-card" data-rating="{{ $review->rating }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h5 class="mb-0">{{ $review->user->name }}</h5>
                                <div class="text-warning">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                </div>
                            </div>
                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="card-text">{{ $review->comment }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted">
                    <i class="bi bi-chat-square-text fs-1"></i>
                    <p class="mt-2">Chưa có đánh giá nào cho sản phẩm này.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>


    <div class="card mt-4">
        <section class="featured-products py-5">
            <div class="container">
                <h2 class="mb-4">Sản phẩm tương tự</h2>
                <div class="product-carousel">
                    @foreach($similarProducts as $product)
                    <div class="product-item">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->images->first())
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
    </div>
</div>

<style>
    /* Định dạng ảnh chính */
    .product-image-gallery img {
        width: 100%;
        height: auto;
        object-fit: contain;
        max-height: 400px;
        margin-bottom: 15px;
    }

    /* Container của ảnh phụ */
    .product-thumbnail-container {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-top: 10px;
    }

    /* Wrapper để chứa và cuộn ảnh */
    .product-thumbnail-wrapper {
        display: flex;
        overflow-x: auto;
        gap: 10px;
        padding: 5px;
        scroll-behavior: smooth;
    }

    /* Ảnh phụ */
    .product-thumbnail-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .product-thumbnail-item img:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Nút điều hướng */
    .product-thumbnail-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        padding: 5px 10px;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.3s;
    }

    .product-thumbnail-nav:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }

    .product-thumbnail-prev {
        left: 5px;
    }

    .product-thumbnail-next {
        right: 5px;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let currentImageIndex = 0;
    let currentThumbnailIndex = 0;
    const thumbnailsPerView = 4;

    function changeProductImage(imageElement) {
        const mainImage = document.querySelector('.product-image-gallery img');
        if (mainImage && imageElement) {
            mainImage.src = imageElement.dataset.src;
            mainImage.alt = imageElement.alt;
        }
    }

    function updateProductThumbnailSelection() {
        document.querySelectorAll('.product-small-img').forEach((img, index) => {
            img.style.opacity = index === currentImageIndex ? '1' : '0.6';
        });
    }

    function scrollProductThumbnails(direction) {
        const thumbnailScroll = document.getElementById('productThumbnailScroll');
        const thumbnails = thumbnailScroll.querySelectorAll('.product-thumbnail-item');
        const maxIndex = thumbnails.length - thumbnailsPerView;

        currentThumbnailIndex = Math.max(0, Math.min(currentThumbnailIndex + direction, maxIndex));
        const translateX = -currentThumbnailIndex * (100 / thumbnailsPerView);
        thumbnailScroll.style.transform = `translateX(${translateX}%)`;
    }

    function addToCart(productId) {
        const quantity = parseInt(document.getElementById('productQuantity').value);

        $.ajax({
            url: "{{ route('cart.add') }}",
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thêm vào giỏ hàng thành công',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Có lỗi xảy ra',
                    text: xhr.responseJSON?.message || 'Không thể thêm vào giỏ hàng'
                });
            }
        });
    }

    function buyNow() {
        Swal.fire({
            title: 'Đang chuyển hướng...',
            text: 'Vui lòng đợi trong giây lát',
            timer: 1000,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        }).then(() => {
            // Add your buy now logic here
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('productQuantity');
        const decreaseBtn = document.getElementById('decreaseQuantity');
        const increaseBtn = document.getElementById('increaseQuantity');

        decreaseBtn.addEventListener('click', () => {
            const currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });

        increaseBtn.addEventListener('click', () => {
            quantityInput.value = parseInt(quantityInput.value) + 1;
        });

        quantityInput.addEventListener('change', () => {
            if (quantityInput.value < 1) quantityInput.value = 1;
        });

        // Rating filter
        document.querySelectorAll('input[name="rating-filter"]').forEach(filter => {
            filter.addEventListener('change', function() {
                const rating = this.id === 'allRatings' ? 'all' : parseInt(this.id.replace('rating', ''));

                document.querySelectorAll('.product-review-card').forEach(card => {
                    const cardRating = parseInt(card.dataset.rating);
                    card.style.display = (rating === 'all' || cardRating === rating) ? 'block' : 'none';
                });
            });
        });

        // Initialize first thumbnail as active
        const firstThumbnail = document.querySelector('.product-small-img');
        if (firstThumbnail) {
            firstThumbnail.style.opacity = '1';
        }
    });
</script>
@endsection