<!-- Thêm địa chỉ modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog">
        <form action="{{ route('profile.add.address') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Địa Chỉ</h5>
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

                    <!-- Form fields -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', Auth::user()->phone) }}">
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
                        <input type="checkbox" name="is_default" class="form-check-input" value="1" {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label">Đặt làm địa chỉ mặc định</label>
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

<!-- Script chỉ cho dynamic select -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        // Setup cho form thêm mới
        setupAddressSelects(
            document.getElementById('id_province'),
            document.getElementById('id_district'),
            document.getElementById('id_ward')
        );

        // Setup cho form edit
        setupAddressSelects(
            document.getElementById('edit_id_province'),
            document.getElementById('edit_id_district'),
            document.getElementById('edit_id_ward')
        );
    });
</script>