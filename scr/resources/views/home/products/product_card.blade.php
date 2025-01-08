<div class="col">
    <div class="card product-card h-100">
        <div class="product-image-container">
            @if($product->price != $product->discounted_price)
            <span class="discount-badge">
                -{{ round((1 - $product->discounted_price / $product->price) * 100) }}%
            </span>
            @endif

            @php
            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
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
                        @if($i <=floor($product->average_rating))
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
<!-- Modal Thêm vào giỏ hàng -->
<div class="modal fade" id="addToCartModal-{{ $product->product_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn số lượng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="product-quantity-control">
                    <button type="button" class="product-quantity-btn" onclick="changeQuantity(-1, 'quantity-{{ $product->product_id }}')">-</button>
                    <input type="number" id="quantity-{{ $product->product_id }}" class="product-quantity-input" value="1" min="1" max="{{ $product->stock }}">
                    <button type="button" class="product-quantity-btn" onclick="changeQuantity(1, 'quantity-{{ $product->product_id }}')">+</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="addToCart('{{ $product->product_id }}')">Thêm vào giỏ</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mua ngay -->
<div class="modal fade" id="buyNowModal-{{ $product->product_id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn số lượng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="product-quantity-control">
                    <button type="button" class="product-quantity-btn" onclick="changeQuantity(-1, 'buyQuantity-{{ $product->product_id }}')">-</button>
                    <input type="number" id="buyQuantity-{{ $product->product_id }}" class="product-quantity-input" value="1" min="1" max="{{ $product->stock }}">
                    <button type="button" class="product-quantity-btn" onclick="changeQuantity(1, 'buyQuantity-{{ $product->product_id }}')">+</button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="buyNow('{{ $product->product_id }}')">Mua ngay</button>
            </div>
        </div>
    </div>
</div>

<script>
    function changeQuantity(change, inputId) {
        let quantityInput = document.getElementById(inputId);
        if (!quantityInput) return;
        let newQuantity = parseInt(quantityInput.value) + change;
        if (newQuantity >= 1 && newQuantity <= parseInt(quantityInput.max)) {
            quantityInput.value = newQuantity;
        }
    }

    function addToCart(productId) {
        let quantity = document.getElementById(`quantity-${productId}`).value;
        fetch("{{ route('cart.add') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        }).then(response => response.json()).then(data => {
            alert(data.message);
            location.reload();
        });
    }

    function buyNow(productId) {
        let quantity = document.getElementById(`buyQuantity-${productId}`).value;
        window.location.href = "{{ route('checkout') }}?product_id=" + productId + "&quantity=" + quantity;
    }
</script>