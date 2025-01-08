<nav class="navbar navbar-expand-lg navbar-top">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="{{asset('assets/images/logo.png')}}" alt="Logo" class="logo">
        </a>
        <div class="d-flex align-items-center">
            <div class="cart-icon me-3 dropdown">
                <a class="nav-link dropdown-toggle" href="{{ route('cart.index') }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-cart3"></i>
                    <span class="badge bg-danger">
                        {{ Auth::check() && Auth::user()->cart ? Auth::user()->cart->items->sum('quantity') : ($guestCartCount ?? 0) }}
                    </span>
                </a>

                <div class="dropdown-menu dropdown-menu-end cart-dropdown">
                    <div class="cart-items">
                        @if(isset($cart) && $cart->items->count() > 0)
                        @foreach($cart->items as $item)
                        <div class="cart-item d-flex align-items-center p-2">
                            
                            <div class="cart-item-details ms-2">
                                <div class="cart-item-name">{{ $item->product->product_name }}</div>
                                <div class="cart-item-price">{{ number_format($item->product->price) }} VNĐ x {{ $item->quantity }}</div>
                            </div>
                        </div>
                        @endforeach
                        <div class="dropdown-divider"></div>
                        <div class="cart-total p-2 d-flex justify-content-between">
                            <strong>Tổng cộng:</strong>
                            <span>{{ number_format($cart->items->sum(function($item) { 
                        return $item->product->price * $item->quantity; 
                    })) }} VNĐ</span>
                        </div>
                        <div class="cart-actions p-2">
                            <a href="{{ route('cart.index') }}" class="btn btn-primary w-100 mb-2">Xem giỏ hàng</a>
                            <a href="{{ route('checkout') }}" class="btn btn-success w-100">Thanh toán</a>
                        </div>
                        @else
                        <div class="text-center p-3">
                            Giỏ hàng trống
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @guest
            <a class="btn btn-register me-2" href="{{route('register')}}">Đăng ký</a>
            <a class="btn btn-login" href="{{route('login')}}">Đăng nhập</a>
            @endguest
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-bottom">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active me-3" href="{{route('home')}}">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{route('products.index')}}">Sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link me-3" href="{{route('about')}}">Giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('contact')}}">Liên hệ</a>
                </li>
            </ul>
        </div>
        <form class="d-flex position-relative me-3" action="{{ route('products.searchResults') }}" method="GET">
            <input class="form-control search-input" type="search" placeholder="Tìm kiếm" aria-label="Search" name="q" required>
            <button class="search-button" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>


        @auth
        <div class="dropdown">
            <a href="#" class="dropdown-toggle text-decoration-none" data-bs-toggle="dropdown">
                <img src="{{ Auth::user()->avatar_path }}" alt="Avatar" class="account-icon">

            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                @if (Auth::user()->role_id == 1)
                <li><a class="dropdown-item" href="{{ route('admin.dashboard.index') }}">Quản trị</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                @else
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">Thông tin tài khoản</a></li>
                <li><a class="dropdown-item" href="{{ route('orders.index') }}">Đơn hàng</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Đăng xuất</button>
                    </form>
                </li>
            </ul>
        </div>
        @endauth
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function($) {
        var engine1 = new Bloodhound({
            remote: {
                url: '/search?value=%QUERY%',
                wildcard: '%QUERY%',
                ajax: {
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                    }
                }
            },
            datumTokenizer: Bloodhound.tokenizers.whitespace('value'),
            queryTokenizer: Bloodhound.tokenizers.whitespace
        });

        $(".search-input").typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            source: engine1.ttAdapter(),
            display: 'product_name',
            templates: {
                suggestion: function(data) {
                    return `
                    <div class="search-suggestion-item">
                     
                        <div class="search-suggestion-details">
                            <span class="search-suggestion-name">${data.product_name}</span>
                          <span class="search-suggestion-price">${data.price} VND</span>
                        </div>
                    </div>
                `;
                },
                empty: [
                    '<div class="empty-message">Không tìm thấy sản phẩm</div>'
                ]
            }
        }).on('typeahead:select', function(event, suggestion) {
            // Navigate to product detail page
            window.location.href = `/products/${suggestion.product_id}`;
        });

        // Add custom CSS for search suggestions
        $('head').append(`
        <style>
            .tt-menu {
                min-width: 300px;
                background-color: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                max-height: 300px;
                overflow-y: auto;
            }
            .tt-suggestion {
                padding: 10px;
                cursor: pointer;
                border-bottom: 1px solid #f0f0f0;
            }
            .tt-suggestion:last-child {
                border-bottom: none;
            }
            .tt-suggestion:hover {
                background-color: #f5f5f5;
            }
            .search-suggestion-item {
                display: flex;
                align-items: center;
            }
            .search-suggestion-image {
                width: 50px;
                height: 50px;
                object-fit: cover;
                margin-right: 10px;
                border-radius: 4px;
            }
            .search-suggestion-details {
                display: flex;
                flex-direction: column;
            }
            .search-suggestion-name {
                font-weight: bold;
                color: #333;
            }
            .search-suggestion-price {
                color: #e74c3c;
                font-size: 0.9em;
            }
            .empty-message {
                padding: 10px;
                color: #888;
                text-align: center;
            }
        </style>
    `);
    });
</script>
<style>
    /* Style riêng cho dropdown giỏ hàng */
    .cart-dropdown {
        width: 300px;
        padding: 0;
        border: none;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 1024;
        position: absolute;
        right: 0;
        left: auto;
    }

    .cart-dropdown.show {
        display: block;
    }

    .cart-items {
        max-height: 400px;
        overflow-y: auto;
    }

    .cart-item {
        border-bottom: 1px solid #f0f0f0;
        padding: 10px;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
    }

    .cart-item-name {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .cart-item-price {
        font-size: 0.8rem;
        color: #666;
    }

    .cart-total {
        background-color: #f8f9fa;
        padding: 10px;
        font-weight: bold;
    }

    .cart-actions {
        padding: 10px;
        background-color: #fff;
    }

    /* Hover effect chỉ cho cart icon */
    @media (min-width: 992px) {
        .cart-icon:hover .cart-dropdown {
            display: block;
        }
    }
</style>

<script>
    $(document).ready(function() {
        var cartUpdateTimeout;
        var isCartUpdating = false;

        // Hàm cập nhật số lượng badge
        function updateCartBadge(count) {
            $('.cart-icon .badge').text(count);
        }

        // Hàm format tiền tệ
        function formatCurrency(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ';
        }

        // Hàm cập nhật nội dung giỏ hàng
        function updateCartContent() {
            if (isCartUpdating) return;

            isCartUpdating = true;

            $.ajax({
                url: '{{ route("cart.getItems") }}',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        var cartHtml = '';

                        if (response.items.length > 0) {
                            response.items.forEach(function(item) {
                                var price = item.product.discounted_price || item.product.price;
                                cartHtml += `
                                <div class="cart-item d-flex align-items-center">
                                    <div class="cart-item-details ms-2">
                                        <div class="cart-item-name">${item.product.product_name}</div>
                                        <div class="cart-item-price">${formatCurrency(price)} x ${item.quantity}</div>
                                    </div>
                                </div>
                            `;
                            });

                            cartHtml += `
                            <div class="dropdown-divider"></div>
                            <div class="cart-total d-flex justify-content-between">
                                <strong>Tổng cộng:</strong>
                                <span>${formatCurrency(response.total)}</span>
                            </div>
                            <div class="cart-actions">
                                <a href="{{ route('cart.index') }}" class="btn btn-primary w-100 mb-2">Xem giỏ hàng</a>
                                <a href="{{ route('checkout') }}" class="btn btn-success w-100">Thanh toán</a>
                            </div>
                        `;
                        } else {
                            cartHtml = '<div class="text-center p-3">Giỏ hàng trống</div>';
                        }

                        $('.cart-items').html(cartHtml);
                        updateCartBadge(response.count);
                    }
                },
                error: function(xhr) {
                    console.error('Lỗi khi cập nhật giỏ hàng:', xhr);
                },
                complete: function() {
                    isCartUpdating = false;
                }
            });
        }

        // Cập nhật khi hover vào giỏ hàng
        $('.cart-icon').hover(function() {
            updateCartContent();
        });

        // Cập nhật khi click vào icon giỏ hàng (cho mobile)
        $('.cart-icon .nav-link').click(function(e) {
            if (window.innerWidth < 992) {
                e.preventDefault();
                updateCartContent();
            }
        });

        // Lắng nghe sự kiện thêm vào giỏ hàng
        $(document).on('cart:updated', function() {
            updateCartContent();
        });

        // Cập nhật định kỳ nếu dropdown đang mở
        setInterval(function() {
            if ($('.cart-dropdown').is(':visible')) {
                updateCartContent();
            }
        }, 5000); // Cập nhật mỗi 5 giây nếu đang mở
    });
</script>