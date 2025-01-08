@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container">
    <h1>Chỉnh sửa Sản phẩm</h1>
    <form action="{{ route('admin.products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="product_name">Tên sản phẩm</label>
            <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="short_description">Mô tả ngắn</label>
            <input type="text" class="form-control" id="short_description" name="short_description" value="{{ $product->short_description }}">
        </div>
        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ $product->price }}" required>
        </div>
        <div class="form-group">
            <label for="stock">Tồn kho</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
        </div>
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select class="form-control" id="category_id" name="category_id" required>
                @foreach($categories as $category)
                <option value="{{ $category->category_id }}" {{ $product->category_id == $category->category_id ? 'selected' : '' }}>
                    {{ $category->category_name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="weight">Trọng lượng</label>
            <input type="number" class="form-control" id="weight" name="weight" step="0.01" value="{{ $product->weight }}">
        </div>
        <div class="form-group">
            <label for="dimensions">Kích thước</label>
            <input type="text" class="form-control" id="dimensions" name="dimensions" value="{{ $product->dimensions }}">
        </div>
        <div class="form-group">
            <label for="material">Chất liệu</label>
            <input type="text" class="form-control" id="material" name="material" value="{{ $product->material }}">
        </div>
        <div class="form-group">
            <label for="origin">Xuất xứ</label>
            <input type="text" class="form-control" id="origin" name="origin" value="{{ $product->origin }}">
        </div>
        <div class="form-group">
            <label for="warranty_period">Thời gian bảo hành (tháng)</label>
            <input type="number" class="form-control" id="warranty_period" name="warranty_period" value="{{ $product->warranty_period }}">
        </div>
        <div class="form-group">
            <label for="status">Trạng thái</label>
            <select class="form-control" id="status" name="status" required>
                <option value="in_stock" {{ $product->status == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                <option value="out_of_stock" {{ $product->status == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
            </select>
        </div>
        <div class="form-group">
            <label for="images">Thêm hình ảnh sản phẩm</label>
            <input type="file" class="form-control-file" id="images" name="images[]" multiple>
        </div>
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
            <button type="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
        </div>
        
    </form>
</div>
@endsection