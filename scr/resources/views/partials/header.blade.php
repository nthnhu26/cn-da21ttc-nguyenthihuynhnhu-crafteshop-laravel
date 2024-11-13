<nav class="navbar navbar-expand-lg bg-light fixed-top">
    <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">
            <img src="{{ asset('images/craftyzen-logo.png') }}" alt="CraftyZen Logo">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ Route::currentRouteName() == 'home' ? 'active' : '' }}" href="{{ route('home') }}">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link {{ Route::currentRouteName() == 'products' ? 'active' : '' }}" href="{{ route('products') }}">Sản phẩm</a></li>
                <li class="nav-item"><a class="nav-link {{ Route::currentRouteName() == 'about' ? 'active' : '' }}" href="{{ route('about') }}">Giới thiệu</a></li>
                <li class="nav-item"><a class="nav-link {{ Route::currentRouteName() == 'contact' ? 'active' : '' }}" href="{{ route('contact') }}">Liên hệ</a></li>
            </ul>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown me-2">
                    <a class="nav-link" href="#" id="searchIcon" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-search"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end p-2" style="min-width: 300px;">
                        <div class="search-container">
                            <form class="search-form" action="{{ route('search') }}" method="GET">
                                <input type="search" name="q" placeholder="Search..." aria-label="Search">
                                <button class="btn" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="cartButton" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-cart"></i> Giỏ hàng (<span id="cartCount">0</span>)
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="cartDropdown">
                        <li>
                            <h6 class="dropdown-header"><a class="nav-link {{ Route::currentRouteName() == 'contact' ? 'active' : '' }}" href="{{ route('cart') }}">Giỏ hàng của bạn</a></h6>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li id="cartItems"></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item">Tổng cộng: <span id="cartTotal">0</span>đ</a></li>
                        <li><a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-2">Thanh toán</a></li>
                    </ul>
                </li>
                <!-- Tài khoản -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="accountButton" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person"></i> Tài khoản
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" id="accountDropdown">
                        @guest
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Đăng nhập</a></li>
                        <li><a class="dropdown-item" href="{{ route('register') }}">Đăng ký</a></li>
                        @else
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a></li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        @endguest
                    </ul>
                </li>
                <li class="nav-item me-2">
                    <button class="theme-toggle" id="themeToggle">
                        <i class="bi bi-sun-fill" id="lightIcon"></i>
                        <i class="bi bi-moon-fill" id="darkIcon" style="display: none;"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Modal Đăng Nhập -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Đăng Nhập</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div id="loginError" class="text-danger mb-3" style="display: none;"></div>
                    <button type="submit" class="btn btn-primary w-100">Đăng Nhập</button>
                </form>
            </div>
        </div>
    </div>
</div>