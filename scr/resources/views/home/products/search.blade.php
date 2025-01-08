@extends('home.layouts.app')

@section('content')
<div class="container my-4">
    <h3 class="mb-3">Kết quả tìm kiếm cho: "{{ $query }}"</h3>

    @if($products->isEmpty())
        <p>Không tìm thấy sản phẩm nào.</p>
    @else
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-3 mb-4">
                    <div class="card product-card h-100">
                        <div class="product-image-container">
                            @if($product->price != $product->discounted_price)
                            <span class="discount-badge">
                                -{{ round((1 - $product->discounted_price / $product->price) * 100) }}%
                            </span>
                            @endif

                            @php
                            $primaryImage = $product->images->first();
                            @endphp

                            @if($primaryImage)
                            <img src="{{ Storage::url($primaryImage->image_url) }}" class="card-img-top" alt="{{ $product->product_name }}">
                            @else
                            <img src="https://placehold.co/400x300" class="card-img-top" alt="{{ $product->product_name }}">
                            @endif

                            <div class="hover-icons">
                                <div class="hover-icon-btn">
                                    <a href="{{ route('products.show', ['id' => $product->product_id]) }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <span class="tooltip-text">Xem chi tiết</span>
                                </div>
                                <div class="hover-icon-btn">
                                    <a href="" data-bs-toggle="modal" data-bs-target="#addToCartModal-{{ $product->product_id }}">
                                        <i class="fas fa-shopping-cart"></i>
                                    </a>
                                    <span class="tooltip-text">Thêm vào giỏ hàng</span>
                                </div>
                                <div class="hover-icon-btn">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#buyNowModal-{{ $product->product_id }}">
                                        <i class="fas fa-bolt"></i>
                                    </a>
                                    <span class="tooltip-text">Mua ngay</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title">{{ $product->product_name }}</h6>
                            <div class="mb-2">
                                <span class="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->average_rating))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i == ceil($product->average_rating) && $product->average_rating - floor($product->average_rating) >= 0.5)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="fas fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span class="ms-2">{{ number_format($product->average_rating, 1) }}</span>
                            </div>
                            <p class="card-text">{{ $product->short_description }}</p>
                            <div class="d-flex align-items-center gap-2">
                                @if($product->price != $product->discounted_price)
                                <span class="original-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                <span class="sale-price">{{ number_format($product->discounted_price, 0, ',', '.') }}đ</span>
                                @else
                                <span class="sale-price">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
