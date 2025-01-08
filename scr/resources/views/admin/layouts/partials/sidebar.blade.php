<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon">
            <img src="assets/images/logo-admin-mini.png" alt="Logo" style="width: 50px;">
        </div>
        <div class="sidebar-brand-text mx-3"><img src="assets/images/logo-admin.png" alt="Logo" style="width: 150px;"></div>
    </a>
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
            <iclass="fas fa-fw fa-tachometer-alt"></iclass=>
            <span>Dashboard</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <!-- Heading -->
    <div class="sidebar-heading">Quản lý sản phẩm</div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('admin.categories.index') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Danh mục</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('admin.products.index')}}">
            <i class="fas fa-fw fa-box"></i>
            <span>Sản phẩm</span>
        </a>
    </li>
    <hr class="sidebar-divider">
    <!-- Nav Item - Quản lý người dùng -->
    <!-- Heading -->
    <div class="sidebar-heading"> Quản lý người dùng </div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.orders.index') }}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Đơn hàng</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Người dùng</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.reviews.index') }}">
            <i class="fas fa-fw fa-star"></i>
            <span>Đánh giá</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.contacts.index')}}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Liên hệ</span></a>
    </li>

    <hr class="sidebar-divider">
    <!-- Nav Item - Quản lý website -->
    <div class="sidebar-heading">Quản lý website</div>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.reports.index')}}">
            <i class="fas fa-fw fa-chart-line"></i>
            <span>Báo cáo</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{route('admin.promotions.index')}}">
            <i class="fas fa-fw fa-tags"></i>
            <span>Khuyến mãi</span></a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.banners.index') }}">
            <i class="fas fa-fw fa-image"></i>
            <span>Banner</span></a>
    </li>


    <hr class="sidebar-divider d-none d-md-block">
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>