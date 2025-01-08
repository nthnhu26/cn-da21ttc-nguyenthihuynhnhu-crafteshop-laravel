@foreach ($products as $product)
    <!-- Add to Cart Modal -->
    <div class="modal fade" id="addToCartModal-{{ $product->product_id }}" tabindex="-1" aria-labelledby="addToCartLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm vào giỏ hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Chọn số lượng cho sản phẩm <strong>{{ $product->product_name }}</strong>.</p>
                    <div class="d-flex align-items-center">
                        <button onclick="updateQuantity('decrease', '{{ $product->product_id }}')" class="btn btn-outline-secondary">-</button>
                        <input type="number" id="quantity-{{ $product->product_id }}" value="1" min="1" class="form-control mx-2 text-center" style="width: 60px;">
                        <button onclick="updateQuantity('increase', '{{ $product->product_id }}')" class="btn btn-outline-secondary">+</button>
                    </div>
                    <p class="mt-3">Tổng cộng: <span id="totalPrice-{{ $product->product_id }}" class="fw-bold">{{ number_format($product->discounted_price ?? $product->price, 0, ',', '.') }} VNĐ</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="addToCart('{{ $product->product_id }}')">Thêm vào giỏ hàng</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Buy Now Modal -->
    <div class="modal fade" id="buyNowModal-{{ $product->product_id }}" tabindex="-1" aria-labelledby="buyNowLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mua ngay</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Chọn số lượng cho sản phẩm <strong>{{ $product->product_name }}</strong>.</p>
                    <div class="d-flex align-items-center">
                        <button onclick="updateQuantity('decrease', '{{ $product->product_id }}')" class="btn btn-outline-secondary">-</button>
                        <input type="number" id="quantity-buy-{{ $product->product_id }}" value="1" min="1" class="form-control mx-2 text-center" style="width: 60px;">
                        <button onclick="updateQuantity('increase', '{{ $product->product_id }}')" class="btn btn-outline-secondary">+</button>
                    </div>
                    <p class="mt-3">Tổng cộng: <span id="totalPrice-buy-{{ $product->product_id }}" class="fw-bold">{{ number_format($product->discounted_price ?? $product->price, 0, ',', '.') }} VNĐ</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="buyNow('{{ $product->product_id }}')">Mua ngay</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
