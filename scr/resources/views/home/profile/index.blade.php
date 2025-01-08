@extends('home.layouts.app')
@section('title', 'Thông tin cá nhân')
@section('content')
<main class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ Auth::user()->avatar_path }}"
                            alt="Ảnh đại diện"
                            class="rounded-circle img-thumbnail mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;">

                        <h5 class="card-title">{{ Auth::user()->name }}</h5>
                        <p class="card-text">{{ Auth::user()->email }}</p>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateAvatarModal">Cập nhật ảnh đại diện</button>
                    </div>
                </div>
                <div class="list-group mt-3" id="profileTabs" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="profile-tab" data-bs-toggle="list" href="#profile-content" role="tab" aria-controls="profile-content"><i class="bi bi-person me-2"></i> Thông tin cá nhân</a>
                    <a class="list-group-item list-group-item-action" id="password-tab" data-bs-toggle="list" href="#password-content" role="tab" aria-controls="password-content"><i class="bi bi-key me-2"></i> Đổi mật khẩu</a>
                    <a class="list-group-item list-group-item-action" id="address-tab" data-bs-toggle="list" href="#address-content" role="tab" aria-controls="address-content"><i class="bi bi-geo-alt me-2"></i> Quản lý địa chỉ</a>
                    <a class="list-group-item list-group-item-action" href="{{ route('orders.index') }}"><i class="bi bi-box-seam me-2"></i> Quản lý đơn hàng</a>
                    <a class="list-group-item list-group-item-action" id="delete-tab" data-bs-toggle="list" href="#delete-content" role="tab" aria-controls="delete-content"><i class="bi bi-trash me-2"></i> Xóa tài khoản</a>
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    @include('home.profile.profile-info')
                    @include('home.profile.change-password')
                    @include('home.profile.manage-addresses')
                    @include('home.profile.profile-delete')
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Include Modals -->
@include('home.profile.modals.update-avatar')
@include('home.profile.modals.edit-address')
@include('home.profile.modals.add-address')

<style>
    .modal-dialog {
        max-width: 500px;
    }

    .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-bottom: 1px solid #dee2e6;
        background-color: #f8f9fa;
        border-radius: 12px 12px 0 0;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        background-color: #f8f9fa;
        border-radius: 0 0 12px 12px;
    }

    .mb-3 {
        position: relative;
        margin-bottom: 15px;
    }

    .password-input-group {
        position: relative;
    }

    .password-input-group input {
        width: 100%;
        padding-right: 45px;
    }

    .password-input-group .input-group-text {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        display: flex;
        align-items: center;
        height: 100%;
        cursor: pointer;
    }

    .bi {
        font-size: 1.1rem;
        color: #6c757d;
    }

    .toggle-password {
        cursor: pointer;
    }

    .invalid-feedback {
        margin-left: 15px;
    }

    .alert {
        margin: 15px;
    }
</style>

<script>
    // Kiểm tra nếu có lỗi, hiển thị tab đổi mật khẩu
    if (document.querySelectorAll('#password-content .is-invalid').length > 0) {
        const passwordTab = new bootstrap.Tab(document.querySelector('#password-tab'));
        passwordTab.show();
    }
    document.addEventListener('DOMContentLoaded', function() {
        function setupPasswordToggle(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (icon && input) {
                icon.parentElement.addEventListener('click', () => {
                    const type = input.getAttribute('type');
                    input.setAttribute('type', type === 'password' ? 'text' : 'password');
                    icon.classList.toggle('bi-eye');
                    icon.classList.toggle('bi-eye-slash');
                });
            }
        }

        setupPasswordToggle('current_password', 'toggleIcon1');
        setupPasswordToggle('new_password', 'toggleIcon2');
        setupPasswordToggle('new_password_confirmation', 'toggleIcon3');


    });
</script>

@endsection