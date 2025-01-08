@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">Thêm Sản Phẩm Mới</h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Sản Phẩm</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm Sản Phẩm</li>
                        </ol>
                    </nav>
                </div>

                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <form class="forms-sample" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    {{-- Hiển thị lỗi chung --}}
                                    @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label>Tên Sản Phẩm <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="product_name"
                                                    placeholder="Nhập tên sản phẩm"
                                                    value="{{ old('product_name') }}" required>
                                                @error('product_name')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Mô Tả Ngắn</label>
                                                <input type="text" class="form-control" name="short_description"
                                                    placeholder="Nhập mô tả ngắn"
                                                    value="{{ old('short_description') }}">
                                                @error('short_description')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label>Mô Tả Chi Tiết</label>
                                                <textarea class="form-control" name="description" rows="8"
                                                    placeholder="Nhập mô tả chi tiết sản phẩm">{{ old('description') }}</textarea>
                                                @error('description')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Hình Ảnh Sản Phẩm</label>
                                                <input type="file" class="form-control" name="images[]" accept="image/*" required>
                                                @error('images.*')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror

                                                {{-- Thêm thẻ img để hiển thị ảnh preview --}}
                                                <div class="mt-3">
                                                    <img src="/api/placeholder/200/200" alt="Xem ảnh" class="img-thumbnail" id="image-preview">
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Giá Bán <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="price"
                                                    placeholder="Nhập giá bán"
                                                    value="{{ old('price') }}" required>
                                                @error('price')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Số Lượng Tồn <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" name="stock"
                                                    placeholder="Nhập số lượng"
                                                    value="{{ old('stock') }}" required>
                                                @error('stock')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Danh Mục <span class="text-danger">*</span></label>
                                                <select class="form-control" name="category_id" required>
                                                    <option value="">Chọn danh mục</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->category_id }}"
                                                        {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                                        {{ $category->category_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Khối Lượng (kg)</label>
                                                <input type="number" step="0.01" class="form-control" name="weight"
                                                    placeholder="Nhập khối lượng"
                                                    value="{{ old('weight') }}">
                                                @error('weight')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Kích Thước</label>
                                                <input type="text" class="form-control" name="dimensions"
                                                    placeholder="D x R x C"
                                                    value="{{ old('dimensions') }}">
                                                @error('dimensions')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Chất Liệu</label>
                                                <input type="text" class="form-control" name="material"
                                                    placeholder="Nhập chất liệu"
                                                    value="{{ old('material') }}">
                                                @error('material')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Xuất Xứ</label>
                                                <input type="text" class="form-control" name="origin"
                                                    placeholder="Nhập xuất xứ"
                                                    value="{{ old('origin') }}">
                                                @error('origin')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Thời Gian Bảo Hành (tháng)</label>
                                                <input type="number" class="form-control" name="warranty_period"
                                                    placeholder="Nhập số tháng"
                                                    value="{{ old('warranty_period') }}">
                                                @error('warranty_period')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary mr-2">
                                            <i class="fas fa-save"></i> Lưu
                                        </button>
                                        <button type="reset" class="btn btn-secondary">
                                            <i class="fas fa-undo"></i> Nhập Lại
                                        </button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelector('input[name="images[]"]').addEventListener('change', function(e) {
        const previewContainer = document.querySelector('#image-preview'); // Đảm bảo ID đúng
        const files = e.target.files;

        if (files.length > 0) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewContainer.src = e.target.result; // Hiển thị ảnh preview
            };
            reader.readAsDataURL(files[0]); // Đọc file đầu tiên
        } else {
            previewContainer.src = '/api/placeholder/200/200'; // Placeholder nếu không có file
        }
    });
</script>
@endsection