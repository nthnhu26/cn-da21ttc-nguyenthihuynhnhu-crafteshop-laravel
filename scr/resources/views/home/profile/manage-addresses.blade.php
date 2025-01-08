<div class="tab-pane fade" id="address-content" role="tabpanel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Quản lý địa chỉ</h3>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
            <i class="bi bi-plus-circle me-2"></i>Thêm địa chỉ
        </button>
    </div>

    <div id="addressList" class="row g-3">
        @if(Auth::user()->addresses->isEmpty())
        <div class="col-12">
            <div class="alert alert-info text-center">
                <p class="mb-0">Chưa có thông tin vận chuyển, thêm địa chỉ vận chuyển cho đơn hàng của bạn.</p>
            </div>
        </div>
        @else
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
                        <p class="card-text mb-3">
                            {{ $address -> phone }}<br>
                            {{ $address->address_detail }}, 
                            {{ $address->ward->name }}, 
                            {{ $address->district->name }}, 
                            {{ $address->province->name }}
                        </p>
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm btn-primary edit-address"
                                data-bs-toggle="modal"
                                data-bs-target="#editAddressModal"
                                data-address-id="{{ $address->address_id }}"
                                data-name="{{ $address->name }}"
                                data-address-detail="{{ $address->address_detail }}"
                                data-province-id="{{ $address->id_province }}"
                                data-district-id="{{ $address->id_district }}"
                                data-ward-id="{{ $address->id_ward }}"
                                data-is-default="{{ $address->is_default ? '1' : '0' }}">
                                <i class="bi bi-pencil"></i> Sửa
                            </button>
                            <button class="btn btn-outline-danger btn-sm delete-address"
                                data-is-default="{{ $address->is_default ? 'true' : 'false' }}"
                                onclick="confirmDelete('{{ $address->address_id }}')">
                                <i class="bi bi-trash me-1"></i>Xóa
                            </button>

                            <form action="{{ route('profile.delete.address', $address->address_id) }}" method="POST" class="d-inline" id="delete-form-{{ $address->address_id }}">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>
</div>
