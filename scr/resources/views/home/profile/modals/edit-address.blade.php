<!-- Modal Update Address -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <form action="" method="POST" id="editAddressForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Địa Chỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Tên</label>
                        <input type="text" id="edit_name" name="name" class="form-control" value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_phone" class="form-label">Số điện thoại</label>
                        <input type="text" id="edit_phone" name="phone" class="form-control" value="{{ old('phone') }}">
                    </div>
                    <div class="mb-3">
                        <label for="edit_address_detail" class="form-label">Chi tiết địa chỉ</label>
                        <textarea id="edit_address_detail" name="address_detail" class="form-control" rows="2" required>{{ old('address_detail') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_id_province" class="form-label">Tỉnh/Thành phố</label>
                        <select id="edit_id_province" name="id_province" class="form-select" required>
                            <option value="">Chọn tỉnh/thành phố</option>
                            @foreach($provinces as $province)
                            <option value="{{ $province->code }}" {{ old('id_province') == $province->code ? 'selected' : '' }}>
                                {{ $province->name }}
                            </option>
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
                        <input type="checkbox" id="edit_is_default" name="is_default" class="form-check-input" value="1">
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

<!-- Script để xử lý việc hiển thị dữ liệu edit -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý khi click nút edit
        document.querySelectorAll('.edit-address').forEach(button => {
            button.addEventListener('click', async function() {
                const addressId = this.dataset.addressId;
                const name = this.dataset.name;
                const addressDetail = this.dataset.addressDetail;
                const provinceId = this.dataset.provinceId;
                const districtId = this.dataset.districtId;
                const wardId = this.dataset.wardId;
                const isDefault = this.dataset.isDefault === '1';

                // Cập nhật action của form
                const form = document.getElementById('editAddressForm');
                form.action = `/profile/address/${addressId}`;

                // Điền dữ liệu vào form
                document.getElementById('edit_name').value = name;
                document.getElementById('edit_address_detail').value = addressDetail;
                document.getElementById('edit_id_province').value = provinceId;
                document.getElementById('edit_is_default').checked = isDefault;

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
    });
</script>