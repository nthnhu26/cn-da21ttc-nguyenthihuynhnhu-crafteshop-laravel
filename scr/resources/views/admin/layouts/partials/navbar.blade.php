<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>
    <div>
        <h2 class="mt-4 font-weight-bold">@yield('header')</h2>
    </div>

    <ul class="navbar-nav ml-auto">


        <li class="nav-item dropdown no-arrow mx-1">
            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">{{ auth()->user()->unreadNotifications->count() }}</span>
            </a>
            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                    Thông báo mới
                </h6>
                @foreach(auth()->user()->unreadNotifications->take(5) as $notification)
                <form action="{{ route('markNotificationAsRead', $notification->id) }}" method="POST" class="notification-form">
                    @csrf
                    <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.contacts.show', $notification->data['contact_id']) }}"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <div class="dropdown-list-image mr-3">
                            <img class="rounded-circle" src="assets/images/avata.jpg" alt="...">
                            <div class="status-indicator bg-success"></div>
                        </div>
                        <div class="font-weight-bold">
                            <div class="text-truncate">{{ $notification->data['name'] }}</div>
                            <div class="small text-gray-500">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    </a>
                </form>
                @endforeach
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('admin.contacts.index') }}">
                    Xem tất cả thông báo
                </a>
            </div>
        </li>
        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->username }}</span>
                <img class="img-profile rounded-circle" src="{{ Auth::user()->avatar_path }}">

            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Thông tin cá nhân
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('home') }}">
                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                    Trang chủ
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Đăng xuất</button>
                </form>

            </div>
        </li>
    </ul>
</nav>
<script>
    $(document).ready(function() {
        $('.notification-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function() {
                    // Cập nhật số lượng thông báo
                    let currentCount = parseInt($('.badge-counter').text());
                    if (currentCount > 0) {
                        $('.badge-counter').text(currentCount - 1);
                    }
                    // Ẩn thông báo đã đọc
                    form.remove();
                }
            });

            // Chuyển hướng đến trang chi tiết
            window.location.href = form.find('a').attr('href');
        });
    });
</script>