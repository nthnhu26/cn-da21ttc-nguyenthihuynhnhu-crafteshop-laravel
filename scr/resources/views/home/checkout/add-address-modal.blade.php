<div class="modal fade" id="addressModal" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chọn địa chỉ giao hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @foreach($addresses as $address)
                <div class="address-item mb-3 p-3 border rounded">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="selected_address"
                            id="address_{{ $address->address_id }}" value="{{ $address->address_id }}"
                            {{ $address->is_default ? 'checked' : '' }}>
                        <label class="form-check-label" for="address_{{ $address->address_id }}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $address->name }}</h6>
                                    <p class="mb-1 text-muted">{{ $address->phone }}</p>
                                    <p class="mb-0 small text-muted">
                                        {{ $address->address_detail }},
                                        {{ $address->ward->name }},
                                        {{ $address->district->name }},
                                        {{ $address->province->name }}
                                    </p>
                                </div>
                                @if($address->is_default)
                                <span class="badge bg-primary">Mặc định</span>
                                @endif
                            </div>
                        </label>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="updateSelectedAddress()">Xác nhận</button>
            </div>
        </div>
    </div>
</div>