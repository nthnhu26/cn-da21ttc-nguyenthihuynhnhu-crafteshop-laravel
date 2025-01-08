@extends('admin.layouts.master')
@section('header')
Thêm Khuyến Mãi Mới
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Thêm Khuyến Mãi Mới</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.promotions.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Tên Khuyến Mãi <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="type">Loại Khuyến Mãi <span class="text-danger">*</span></label>
                            <select id="type" name="type"
                                class="form-control @error('type') is-invalid @enderror"
                                required>
                                <option value="">-- Chọn loại khuyến mãi --</option>
                                <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>
                                    Giảm giá theo % cho sản phẩm
                                </option>
                                <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>
                                    Giảm giá cố định cho đơn hàng
                                </option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="value">Giá Trị Giảm Giá <span class="text-danger">*</span></label>
                            <input type="number" id="value" name="value"
                                step="0.01"
                                class="form-control @error('value') is-invalid @enderror"
                                value="{{ old('value') }}" required>
                            @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6" id="min-order-amount-field" style="display: none;">
                        <div class="form-group">
                            <label for="min_order_amount">Giá Trị Đơn Hàng Tối Thiểu</label>
                            <input type="number" id="min_order_amount" name="min_order_amount"
                                step="0.01"
                                class="form-control @error('min_order_amount') is-invalid @enderror"
                                value="{{ old('min_order_amount') }}">
                            @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row" id="code-field">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="code">Mã Giảm Giá <span class="text-danger">*</span></label>
                            <input type="text" id="code" name="code"
                                class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code') }}">
                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="max_quantity">Số Lượng Mã <span class="text-danger">*</span></label>
                            <input type="number" id="max_quantity" name="max_quantity"
                                class="form-control @error('max_quantity') is-invalid @enderror"
                                value="{{ old('max_quantity') }}">
                            @error('max_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start_date">Ngày Bắt Đầu <span class="text-danger">*</span></label>
                            <input type="datetime-local" id="start_date" name="start_date"
                                class="form-control @error('start_date') is-invalid @enderror"
                                value="{{ old('start_date') }}" required>
                            @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end_date">Ngày Kết Thúc <span class="text-danger">*</span></label>
                            <input type="datetime-local" id="end_date" name="end_date"
                                class="form-control @error('end_date') is-invalid @enderror"
                                value="{{ old('end_date') }}" required>
                            @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div id="product-fields">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Chọn Danh Mục</label>
                                <select id="category_id" name="category_id"
                                    class="form-control @error('category_id') is-invalid @enderror">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">
                                        {{ $category->category_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div id="product-group">
                        <div class="form-group">
                            <label>Sản Phẩm Đã Chọn</label>
                            <div id="selected-products" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                <p class="text-muted">Chưa có sản phẩm nào được chọn</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Chọn Sản Phẩm</label>
                            <div class="mb-2">
                                <label>
                                    <input type="checkbox" id="select_all_products">
                                    Chọn tất cả sản phẩm
                                </label>
                            </div>
                            <div id="product-list" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px;">
                                <p class="text-muted">Vui lòng chọn danh mục để hiển thị sản phẩm</p>
                            </div>
                            @error('product_ids')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group text-right">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Lưu Khuyến Mãi
                    </button>
                    <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let selectedProducts = {};

        function handlePromotionTypeChange() {
            const type = $('#type').val();

            if (type === 'percent') {
                $('#product-fields').show();
                $('#min-order-amount-field').hide();
                $('#code-field').hide();
                $('#value').attr({
                    min: 0,
                    max: 100,
                    step: 0.01
                });
            } else if (type === 'fixed') {
                $('#product-fields').hide();
                $('#min-order-amount-field').show();
                $('#code-field').show();
                $('#value').attr({
                    min: 0,
                    step: 0.01
                });
            } else {
                $('#product-fields').hide();
                $('#min-order-amount-field').hide();
                $('#code-field').hide();
            }
        }

        $('#type').on('change', handlePromotionTypeChange);

        handlePromotionTypeChange();

        function updateSelectedProductsView() {
            let html = '';
            for (let categoryId in selectedProducts) {
                for (let productId in selectedProducts[categoryId]) {
                    html += `
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input selected-product" 
                                   name="product_ids[]" 
                                   id="selected_product_${productId}" 
                                   value="${productId}" checked>
                            <label class="form-check-label" for="selected_product_${productId}">
                                ${selectedProducts[categoryId][productId]}
                            </label>
                        </div>
                    `;
                }
            }
            if (html === '') {
                html = '<p class="text-muted">Chưa có sản phẩm nào được chọn</p>';
            }
            $('#selected-products').html(html);
        }

        $('#category_id').on('change', function() {
            const categoryId = $(this).val();
            if (categoryId) {
                $('#product-group').show();
                $('#product-list').html('<p>Đang tải sản phẩm...</p>');

                $.ajax({
                    url: `/admin/categories/${categoryId}/products`,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        let html = '';
                        if (data.length === 0) {
                            html = '<p>Không có sản phẩm nào trong danh mục này.</p>';
                        } else {
                            data.forEach(product => {
                                const isChecked = selectedProducts[categoryId] && selectedProducts[categoryId][product.product_id];
                                html += `
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input product-checkbox" 
                                               data-category-id="${categoryId}"
                                               data-product-id="${product.product_id}" 
                                               id="product_${product.product_id}" 
                                               ${isChecked ? 'checked' : ''}>
                                        <label class="form-check-label" for="product_${product.product_id}">
                                            ${product.product_name}
                                        </label>
                                    </div>
                                `;
                            });
                        }
                        $('#product-list').html(html);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        $('#product-list').html('<p class="text-danger">Không thể tải sản phẩm. Lỗi: ' + error + '</p>');
                    }
                });
            } else {
                $('#product-group').hide();
            }
        });

        $(document).on('change', '.product-checkbox', function() {
            const categoryId = $(this).data('category-id');
            const productId = $(this).data('product-id');
            const productName = $(this).next('label').text().trim();

            if (!selectedProducts[categoryId]) {
                selectedProducts[categoryId] = {};
            }

            if ($(this).is(':checked')) {
                selectedProducts[categoryId][productId] = productName;
            } else {
                delete selectedProducts[categoryId][productId];
                if (Object.keys(selectedProducts[categoryId]).length === 0) {
                    delete selectedProducts[categoryId];
                }
            }

            updateSelectedProductsView();
        });

        $(document).on('change', '.selected-product', function() {
            const productId = $(this).val();
            for (let categoryId in selectedProducts) {
                if (selectedProducts[categoryId][productId]) {
                    delete selectedProducts[categoryId][productId];
                    if (Object.keys(selectedProducts[categoryId]).length === 0) {
                        delete selectedProducts[categoryId];
                    }
                    break;
                }
            }
            updateSelectedProductsView();
            $(`#product_${productId}`).prop('checked', false);
        });

        $('#select_all_products').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('#product-list .product-checkbox').prop('checked', isChecked).trigger('change');
        });
    });
</script>
@endsection