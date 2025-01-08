@extends('home.layouts.app')
@section('title', 'Thông tin cá nhân')
@section('content')
<main class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ Auth::user()->avatar_url ? asset('storage/' . Auth::user()->avatar_url) : 'https://via.placeholder.com/150' }}"
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
                </div>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profile-content" role="tabpanel" aria-labelledby="profile-tab">
                        <h3>Thông tin cá nhân</h3>
                        <form action="{{ route('profile.update.info') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', Auth::user()->name) }}">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email"
                                    value="{{ Auth::user()->email }}" disabled>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                        </form>
                    </div>

                    <div class="tab-pane fade" id="password-content" role="tabpanel" aria-labelledby="password-tab">
                        <h3>Đổi mật khẩu</h3>
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif
                        <form action="{{ route('profile.change.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Mật Khẩu Hiện Tại</label>
                                <div class="password-input-group">
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" id="current_password" required>
                                    <span class="input-group-text toggle-password">
                                        <i class="bi bi-eye-slash" id="toggleIcon1"></i>
                                    </span>
                                </div>
                                @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật Khẩu Mới</label>
                                <div class="password-input-group">
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" id="new_password" required>
                                    <span class="input-group-text toggle-password">
                                        <i class="bi bi-eye-slash" id="toggleIcon2"></i>
                                    </span>
                                </div>
                                @error('new_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="new_password_confirmation" class="form-label">Xác Nhận Mật Khẩu Mới</label>
                                <div class="password-input-group">
                                    <input type="password" class="form-control @error('new_password_confirmation') is-invalid @enderror" name="new_password_confirmation" id="new_password_confirmation" required>
                                    <span class="input-group-text toggle-password">
                                        <i class="bi bi-eye-slash" id="toggleIcon3"></i>
                                    </span>
                                </div>
                                @error('new_password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Đổi Mật Khẩu</button>
                        </form>
                    </div>

                    <!-- Address List View -->
                    <div class="tab-pane fade" id="address-content" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Quản lý địa chỉ</h3>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="bi bi-plus-circle me-2"></i>Thêm địa chỉ
                            </button>
                        </div>

                        <div id="addressList" class="row g-3">
                            @foreach(Auth::user()->addresses as $address)
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">{{ $address->name }}</h5>
                                            @if($address->is_default)
                                            <span class="badge bg-primary">Mặc định</span>
                                            @endif
                                        </div>
                                        <p class="card-text mb-3">{{ $address->address_detail }}, {{ $address->ward->name }}, {{ $address->district->name }}, {{ $address->province->name }}</p>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-outline-primary btn-sm edit-address"
                                                data-address-id="{{ $address->address_id }}"
                                                data-name="{{ $address->name }}"
                                                data-address-detail="{{ $address->address_detail }}"
                                                data-province-id="{{ $address->id_province }}"
                                                data-district-id="{{ $address->id_district }}"
                                                data-ward-id="{{ $address->id_ward }}"
                                                data-is-default="{{ $address->is_default ? 'true' : 'false' }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editAddressModal">
                                                <i class="bi bi-pencil me-1"></i>Sửa
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm delete-address"
                                                data-address-id="{{ $address->address_id }}"
                                                data-is-default="{{ $address->is_default ? 'true' : 'false' }}">
                                                <i class="bi bi-trash me-1"></i>Xóa
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Modal cập nhật ảnh đại diện -->
<div class="modal fade" id="updateAvatarModal" tabindex="-1" aria-labelledby="updateAvatarModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateAvatarModalLabel">Cập nhật ảnh đại diện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateAvatarForm" action="{{ route('profile.update.avatar') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="avatar" class="form-label">Chọn ảnh đại diện mới</label>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" form="updateAvatarForm" class="btn btn-primary">Cập nhật</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa địa chỉ -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="editAddressForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Địa Chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_address_id" name="address_id">

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Tên</label>
                        <input type="text" id="edit_name" name="name" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_address_detail" class="form-label">Chi tiết địa chỉ</label>
                        <textarea id="edit_address_detail" name="address_detail" class="form-control" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_province" class="form-label">Tỉnh/Thành phố</label>
                        <select id="edit_id_province" name="id_province" class="form-select" required>
                            <option value="">Chọn tỉnh/thành phố</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->code }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_district" class="form-label">Quận/Huyện</label>
                        <select id="edit_id_district" name="id_district" class="form-select" required>
                            <option value="">Chọn quận/huyện</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_id_ward" class="form-label">Phường/Xã</label>
                        <select id="edit_id_ward" name="id_ward" class="form-select" required>
                            <option value="">Chọn phường/xã</option>
                        </select>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" id="edit_is_default" name="is_default" class="form-check-input">
                        <label for="edit_is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal thêm địa chỉ -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('profile.add.address') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Địa Chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên</label>
                        <input type="text" name="name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="address_detail" class="form-label">Chi tiết địa chỉ</label>
                        <textarea name="address_detail" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="id_province" class="form-label">Tỉnh/Thành phố</label>
                        <select name="id_province" id="id_province" class="form-select" required>
                            <option value="">Chọn tỉnh/thành phố</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->code }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_district" class="form-label">Quận/Huyện</label>
                        <select name="id_district" id="id_district" class="form-select" required>
                            <option value="">Chọn quận/huyện</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="id_ward" class="form-label">Phường/Xã</label>
                        <select name="id_ward" id="id_ward" class="form-select" required>
                            <option value="">Chọn phường/xã</option>
                        </select>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="is_default" id="is_default" class="form-check-input">
                        <label for="is_default" class="form-check-label">Đặt làm địa chỉ mặc định</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>

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

        const setupAddressSelects = (provinceSelect, districtSelect, wardSelect) => {
            provinceSelect.addEventListener('change', async function() {
                const provinceCode = this.value;
                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                if (!provinceCode) return;

                try {
                    const response = await fetch(`/profile/districts/${provinceCode}`);
                    const districts = await response.json();
                    districts.forEach(district => {
                        districtSelect.innerHTML += `<option value="${district.code}">${district.name}</option>`;
                    });
                } catch (error) {
                    console.error('Error loading districts:', error);
                }
            });

            districtSelect.addEventListener('change', async function() {
                const districtCode = this.value;
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                if (!districtCode) return;

                try {
                    const response = await fetch(`/profile/wards/${districtCode}`);
                    const wards = await response.json();
                    wards.forEach(ward => {
                        wardSelect.innerHTML += `<option value="${ward.code}">${ward.name}</option>`;
                    });
                } catch (error) {
                    console.error('Error loading wards:', error);
                }
            });
        };

        setupAddressSelects(
            document.getElementById('id_province'),
            document.getElementById('id_district'),
            document.getElementById('id_ward')
        );

        setupAddressSelects(
            document.getElementById('edit_id_province'),
            document.getElementById('edit_id_district'),
            document.getElementById('edit_id_ward')
        );

        document.querySelectorAll('.delete-address').forEach(button => {
            button.addEventListener('click', async function() {
                if (!confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')) return;

                const addressId = this.dataset.addressId;

                try {
                    const response = await fetch(`/profile/address/${addressId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();

                    if (result.success) {
                        this.closest('.col-md-6').remove();
                    }
                } catch (error) {
                    console.error('Error deleting address:', error);
                }
            });
        });

        document.querySelectorAll('.edit-address').forEach(button => {
            button.addEventListener('click', async function() {
                const addressId = this.dataset.addressId;
                const name = this.dataset.name;
                const addressDetail = this.dataset.addressDetail;
                const provinceId = this.dataset.provinceId;
                const districtId = this.dataset.districtId;
                const wardId = this.dataset.wardId;

                const form = document.getElementById('editAddressForm');
                form.action = `/profile/address/${addressId}`;

                document.getElementById('edit_address_id').value = addressId;
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_address_detail').value = addressDetail;
                document.getElementById('edit_id_province').value = provinceId;

                try {
                    // Load districts
                    const districtResponse = await fetch(`/profile/districts/${provinceId}`);
                    const districts = await districtResponse.json();
                    const districtSelect = document.getElementById('edit_id_district');
                    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    districts.forEach(district => {
                        const selected = district.code == districtId ? 'selected' : '';
                        districtSelect.innerHTML += `<option value="${district.code}" ${selected}>${district.name}</option>`;
                    });

                    // Load wards
                    const wardResponse = await fetch(`/profile/wards/${districtId}`);
                    const wards = await wardResponse.json();
                    const wardSelect = document.getElementById('edit_id_ward');
                    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    wards.forEach(ward => {
                        const selected = ward.code == wardId ? 'selected' : '';
                        wardSelect.innerHTML += `<option value="${ward.code}" ${selected}>${ward.name}</option>`;
                    });
                } catch (error) {
                    console.error('Error loading address data:', error);
                }
            });
        });

        // Handle form submission
        document.getElementById('editAddressForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('_method', 'PUT');

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    window.location.reload();
                    bootstrap.Modal.getInstance(document.getElementById('editAddressModal')).hide();
                }
            } catch (error) {
                console.error('Error updating address:', error);
            }
        });
    });
</script>

@endsection