@extends('home.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-5">Giỏ hàng của bạn</h1>

    @if($cartItems->isEmpty())
    <div class="text-center">
        <p class="text-muted">Giỏ hàng của bạn hiện đang trống!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
    @else
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="mb-4">Sản phẩm trong giỏ hàng</h2>

                    <div class="table-responsive">
                        <table class="table align-middle table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cartItems as $item)
                                <tr data-cart-item-id="{{ $item->cart_item_id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product->images->isNotEmpty())
                                            <img src="{{ Storage::url($item->product->images->first()->image_url) }}"
                                                alt="{{ $item->product->product_name }}"
                                                class="img-thumbnail me-3"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <h6 class="fw-bold">{{ $item->product->product_name }}</h6>
                                                <small class="text-muted">Mã SP: {{ $item->product->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->product->price != $item->product->discounted_price)
                                        <span class="text-danger fw-bold">{{ number_format($item->product->discounted_price, 0, ',', '.') }} ₫</span>
                                        <br>
                                        <small class="text-muted text-decoration-line-through">{{ number_format($item->product->price, 0, ',', '.') }} ₫</small>
                                        @else
                                        <span class="text-danger fw-bold">{{ number_format($item->product->price, 0, ',', '.') }} ₫</span>
                                        @endif

                                    </td>
                                    <td>
                                        <div class="input-group" style="max-width: 120px;">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-type="minus">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" name="quantity" class="form-control text-center quantity-input"
                                                value="{{ $item->quantity }}"
                                                data-price="{{ $item->product->getDiscountedPriceAttribute() }}"
                                                min="1"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" data-type="plus">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="item-total fw-bold">{{ number_format($item->product->getDiscountedPriceAttribute() * $item->quantity) }} ₫</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" data-remove-cart-item>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <form id="clearCartForm" action="{{ route('cart.clear') }}" method="POST" class="text-end">
                        @csrf
                        @method('DELETE')
                        <button type="button" onclick="confirmClearCart()" class="btn btn-danger">Xóa tất cả</button>
                    </form>
                </div>
            </div>

            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
            </a>
        </div>

        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2>Tóm tắt đơn hàng</h2>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Tổng cộng:</strong>
                        <strong id="cartTotal">{{ number_format($cartItems->sum(fn($item) => $item->product->getDiscountedPriceAttribute() * $item->quantity), 0, ',', '.') }} ₫</strong>
                    </div>
                    <a href="{{ route('checkout') }}" class="btn btn-success w-100">Thanh toán ngay</a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityButtons = document.querySelectorAll('.quantity-btn');
        const deleteButtons = document.querySelectorAll('button[data-remove-cart-item]');
        const cartTotalElement = document.getElementById('cartTotal');

        // Cập nhật tổng tiền
        function updateCartTotal() {
            let total = 0;
            document.querySelectorAll('.item-total').forEach(el => {
                total += parseFloat(el.textContent.replace(/\D/g, ''));
            });
            cartTotalElement.textContent = total.toLocaleString() + ' ₫';
        }

        // Xử lý tăng giảm số lượng
        quantityButtons.forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type;
                const row = this.closest('tr');
                const input = row.querySelector('.quantity-input');
                const price = parseFloat(input.dataset.price);
                let quantity = parseInt(input.value) || 1;

                if (type === 'plus') quantity++;
                else if (type === 'minus' && quantity > 1) quantity--;

                input.value = quantity;
                row.querySelector('.item-total').textContent = (price * quantity).toLocaleString() + ' ₫';
                updateCartTotal();
            });
        });

        // Xử lý xóa sản phẩm
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                row.remove();
                updateCartTotal();
            });
        });
    });
</script>
@endsection