@extends('home.layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-between">
        <!-- Left Column -->
        <div class="col-lg-6">
            <!-- Delivery Address Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title fw-bold mb-0">
                            <i class="bi bi-geo-alt-fill text-primary me-2"></i>Địa chỉ giao hàng
                        </h5>
                        <div class="btn-group">
                            <button class="btn btn-outline-success btn-sm me-2">
                                <i class="bi bi-pencil-square"></i> Thay đổi
                            </button>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-circle me-1"></i>Thêm mới
                            </button>
                        </div>
                    </div>

                    <!-- Addresses Container -->
                    <div id="addressesContainer">
                        @foreach($addresses as $address)
                        <div class="address-item mb-3">
                            <div class="form-check p-3 bg-light rounded border hover-shadow">
                                <input class="form-check-input address-selector"
                                    type="radio"
                                    name="selected_address"
                                    id="address_{{ $address->address_id }}"
                                    value="{{ $address->address_id }}"
                                    data-province="{{ $address->province->name }}"
                                    data-district="{{ $address->district->name }}"
                                    {{ $address->is_default ? 'checked' : '' }}>
                                <label class="form-check-label w-100" for="address_{{ $address->address_id }}">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="fw-bold mb-2">{{ $address->name }}</h6>
                                            <p class="mb-1"><i class="bi bi-telephone me-2"></i>{{ $address->phone }}</p>
                                            <p class="mb-0 text-muted">
                                                {{ $address->address_detail }},
                                                {{ $address->ward->name }},
                                                {{ $address->district->name }},
                                                {{ $address->province->name }}
                                            </p>
                                        </div>
                                        @if($address->is_default)
                                        <span class="badge text-danger">Mặc định</span>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div id="addressMessage" class="mt-3"></div>
                </div>
            </div>

            <!-- Payment Methods Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-credit-card text-primary me-2"></i>Phương thức thanh toán
                    </h5>
                    <div class="payment-methods">
                        <div class="form-check mb-3 p-3 border rounded hover-shadow">
                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                            <label class="form-check-label w-100" for="cod">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-cash-stack fs-3 text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Thanh toán khi nhận hàng (COD)</h6>
                                        <small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <div class="form-check p-3 border rounded hover-shadow">
                            <input class="form-check-input" type="radio" name="payment_method" id="momo" value="Momo">
                            <label class="form-check-label w-100" for="momo">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-wallet2 fs-3 text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Ví MoMo</h6>
                                        <small class="text-muted">Thanh toán qua ví điện tử MoMo</small>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Note -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-pencil text-primary me-2"></i>Ghi chú đơn hàng
                    </h5>
                    <textarea class="form-control" id="orderNote" rows="3"
                        placeholder="Nhập ghi chú cho đơn hàng (không bắt buộc)"></textarea>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 position-sticky" style="top: 2rem;">
                <div class="card-body">
                    <h5 class="fw-bold mb-4">
                        <i class="bi bi-cart-check text-primary me-2"></i>Tóm tắt đơn hàng
                    </h5>

                    <!-- Products List -->
                    <div class="order-items mb-4">
                        @foreach($cart->items as $item)
                        <div class="d-flex align-items-center p-2 border rounded mb-3 hover-shadow">
                            <img src="{{ Storage::url($item->product->images->first()->image_url) }}"
                                alt="{{ $item->product->name }}"
                                class="rounded me-3"
                                style="width: 64px; height: 64px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1 text-truncate">{{ $item->product->product_name }}</h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">SL: {{ $item->quantity }}     Giá gốc: {{number_format($item->product->price)}}₫</span>
                                    <span class="fw-bold text-primary">
                                        {{ number_format($item->product->getDiscountedPrice() * $item->quantity) }}₫
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Promotion Code -->
                    <div class="mb-4">
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-primary w-100 dropdown-toggle d-flex align-items-center justify-content-between"
                                type="button"
                                id="promotionDropdown"
                                data-bs-toggle="dropdown">
                                <span><i class="bi bi-ticket-perforated me-2"></i>Chọn mã giảm giá</span>
                            </button>
                            <ul class="dropdown-menu w-100 p-2">
                                @foreach($availablePromotions as $promotion)
                                <li>
                                    <button class="dropdown-item p-2 rounded" onclick="applyPromotion('{{ $promotion->code }}')">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="text-primary">{{ $promotion->code }}</strong>
                                                <small class="d-block text-muted">
                                                    Đơn tối thiểu {{ number_format($promotion->min_amount) }}₫
                                                </small>
                                            </div>
                                            <span class="badge bg-primary">
                                                @if($promotion->type === 'percent')
                                                Giảm {{ $promotion->value }}%
                                                @else
                                                Giảm {{ number_format($promotion->value) }}₫
                                                @endif
                                            </span>
                                        </div>
                                    </button>
                                </li>
                                @if(!$loop->last)
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        <div id="promotionMessage" class="small mt-2"></div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-light p-3 rounded">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <span id="subtotal">{{ number_format($subtotal) }}₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển</span>
                            <span id="shipping">Đang tính...</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success" id="discountContainer"
                            style="display: none;">
                            <span>Giảm giá</span>
                            <span id="discount">-0₫</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                            <h5 class="fw-bold mb-0">Tổng cộng</h5>
                            <h5 class="fw-bold mb-0 text-primary" id="total">Đang tính...</h5>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button type="button" class="btn btn-primary w-100 mt-4 py-3 fw-bold" id="checkoutBtn"
                        onclick="placeOrder()">
                        <i class="bi bi-bag-check me-2"></i>Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('home.profile.modals.add-address')
@endsection

<script>
    function placeOrder() {
        // Get the selected address
        const selectedAddress = document.querySelector('input[name="selected_address"]:checked');

        // Debug check
        console.log("Selected address element:", selectedAddress);
        console.log("Selected address value:", selectedAddress ? selectedAddress.value : 'none');

        // Validate address selection
        if (!selectedAddress || !selectedAddress.value) {
            showAlert('error', 'Vui lòng chọn địa chỉ giao hàng', 'addressMessage');
            return;
        }

        const orderData = {
            selected_address: selectedAddress.value,
            payment_method: document.querySelector('input[name="payment_method"]:checked').value,
            note: document.getElementById('orderNote').value,
            // Don't send coupon_code if it's the default text
            coupon_code: document.querySelector('#promotionDropdown').textContent.trim() === 'Chọn mã giảm giá' ?
                null :
                document.querySelector('#promotionDropdown').textContent.trim(),
            customer_note: document.getElementById('orderNote').value
        };

        const checkoutBtn = document.getElementById('checkoutBtn');
        const originalText = checkoutBtn.innerHTML;
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';

        // Debug the data being sent
        console.log('Sending order data:', orderData);

        fetch('/checkout/place-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => {
                console.log('Raw response:', response);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);

                if (data.success) {
                    window.location.href = `/checkout/success/${data.order_id}`;
                } else {
                    checkoutBtn.disabled = false;
                    checkoutBtn.innerHTML = originalText;
                    showAlert('error', data.message || 'Có lỗi xảy ra khi đặt hàng', 'addressMessage');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = originalText;
                showAlert('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại sau.', 'addressMessage');
            });
    }

    // Add this function to handle address selection visibility
    document.addEventListener('DOMContentLoaded', function() {
        // Show only default address initially
        const addresses = document.querySelectorAll('.address-item');
        addresses.forEach(addr => {
            const radio = addr.querySelector('input[type="radio"]');
            if (!radio.checked) {
                addr.style.display = 'none';
            }
        });

        // Toggle address visibility
        const toggleBtn = document.querySelector('.btn-outline-success');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                addresses.forEach(addr => {
                    addr.style.display = addr.style.display === 'none' ? 'block' : 'none';
                });
                this.textContent = this.textContent.includes('Thay đổi') ? 'Ẩn bớt' : 'Thay đổi';
            });
        }

        // Ensure at least one address is selected
        const defaultAddress = document.querySelector('input[name="selected_address"][checked]');
        if (!defaultAddress && addresses.length > 0) {
            const firstAddress = addresses[0].querySelector('input[name="selected_address"]');
            if (firstAddress) {
                firstAddress.checked = true;
            }
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize shipping calculation based on default address
        const defaultAddress = document.querySelector('input[name="selected_address"]:checked');
        if (defaultAddress) {
            updateShipping(defaultAddress.dataset.province);
        }

        // Add listeners to address radio buttons
        document.querySelectorAll('.address-selector').forEach(radio => {
            radio.addEventListener('change', function() {
                updateShipping(this.dataset.province);
            });
        });
        
    });

    function updateShipping(province) {
        const shippingRates = {
            'mekong': ['An Giang', 'Bạc Liêu', 'Bến Tre', 'Cà Mau', 'Cần Thơ',
                'Đồng Tháp', 'Hậu Giang', 'Kiên Giang', 'Long An',
                'Sóc Trăng', 'Tiền Giang', 'Trà Vinh', 'Vĩnh Long'
            ],
            'rates': {
                'mekong': 10000,
                'other': 20000
            }
        };

        const shipping = shippingRates.mekong.includes(province) ?
            shippingRates.rates.mekong :
            shippingRates.rates.other;

        document.getElementById('shipping').textContent = `${shipping.toLocaleString('vi-VN')}₫`;
        updateTotals();
    }

    function updateTotals() {
        const subtotal = parseFloat(document.getElementById('subtotal').textContent.replace(/[^\d]/g, ''));
        const shipping = parseFloat(document.getElementById('shipping').textContent.replace(/[^\d]/g, ''));
        const discountElement = document.getElementById('discount');
        const discount = discountElement ? parseFloat(discountElement.textContent.replace(/[^\d-]/g, '')) : 0;

        const total = subtotal + shipping - Math.abs(discount);
        document.getElementById('total').textContent = `${total.toLocaleString('vi-VN')}₫`;
    }

    function applyPromotion(code) {
        console.log('Applying promotion:', code);

        fetch('/checkout/check-coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    coupon_code: code
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Promotion response:', data);
                if (data.success) {
                    document.getElementById('discountContainer').style.display = 'flex';
                    document.getElementById('discount').textContent = `-${data.discount.toLocaleString('vi-VN')}₫`;
                    updateTotals();
                    showAlert('success', data.message, 'promotionMessage');
                } else {
                    showAlert('error', data.message || 'Không thể áp dụng mã giảm giá', 'promotionMessage');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('error', 'Có lỗi xảy ra khi áp dụng mã giảm giá', 'promotionMessage');
            });
    }

    // function placeOrder() {
    //     const selectedAddress = document.querySelector('input[name="selected_address"]:checked');
    //     if (!selectedAddress) {
    //         showAlert('error', 'Vui lòng chọn địa chỉ giao hàng', 'addressMessage');
    //         return;
    //     }

    //     const orderData = {
    //         selected_address: selectedAddress.value,
    //         payment_method: document.querySelector('input[name="payment_method"]:checked').value,
    //         note: document.getElementById('orderNote').value,
    //         // Add these lines to include coupon information
    //         coupon_code: document.querySelector('#promotionDropdown').textContent.trim(),
    //         customer_note: document.getElementById('orderNote').value
    //     };

    //     const checkoutBtn = document.getElementById('checkoutBtn');
    //     const originalText = checkoutBtn.innerHTML;
    //     checkoutBtn.disabled = true;
    //     checkoutBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Đang xử lý...';

    //     // For debugging
    //     console.log('Sending order data:', orderData);

    //     fetch('/checkout/place-order', {
    //             method: 'POST',
    //             headers: {
    //                 'Content-Type': 'application/json',
    //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //                 'Accept': 'application/json'
    //             },
    //             body: JSON.stringify(orderData)
    //         })
    //         .then(response => {
    //             // For debugging
    //             console.log('Raw response:', response);
    //             return response.json();
    //         })
    //         .then(data => {
    //             // For debugging
    //             console.log('Response data:', data);

    //             if (data.success) {
    //                 window.location.href = `/checkout/success/${data.order_id}`;
    //             } else {
    //                 checkoutBtn.disabled = false;
    //                 checkoutBtn.innerHTML = originalText;
    //                 showAlert('error', data.message || 'Có lỗi xảy ra khi đặt hàng', 'addressMessage');
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //             checkoutBtn.disabled = false;
    //             checkoutBtn.innerHTML = originalText;
    //             showAlert('error', 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại sau.', 'addressMessage');
    //         });
    // }

    function showAlert(type, message, containerId) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const container = document.getElementById(containerId);

        // Clear any existing alerts
        container.innerHTML = '';

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
        alertDiv.role = 'alert';
        alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

        container.appendChild(alertDiv);
    }
</script>