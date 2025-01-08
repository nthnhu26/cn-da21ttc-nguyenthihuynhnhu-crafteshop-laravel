@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h3 class="m-0 font-weight-bold text-primary">{{ $product->product_name }}</h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card-body">
        {{-- Hiển thị ảnh chính (nếu có) --}}
        @php
            $mainImage = $product->images->where('is_primary', true)->first();
        @endphp

        @if ($mainImage)
            <div class="main-image-container">
                <h4 class="font-weight-bold">Ảnh chính</h4>
                <img src="{{ asset('storage/' . $mainImage->image_url) }}" 
                     alt="Ảnh chính" class="main-image">
            </div>
        @endif

        {{-- Hiển thị danh sách ảnh phụ (loại bỏ ảnh chính) --}}
        <h4 class="mt-4 font-weight-bold">Hình ảnh sản phẩm</h4>
        <div class="image-gallery">
            @foreach($product->images->where('image_id', '!=', $mainImage ? $mainImage->image_id : null) as $image)
                <div class="image-item">
                    <img src="{{ asset('storage/' . $image->image_url) }}" alt="Ảnh sản phẩm">

                    <div class="button-group">
                        {{-- Chọn làm ảnh chính --}}
                        <form action="{{ route('admin.products.set-main-image', [$product->product_id, $image->image_id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">
                                <i class="fas fa-star"></i> Đặt làm ảnh chính
                            </button>
                        </form>

                        {{-- Xóa ảnh --}}
                        <form action="{{ route('admin.products.delete-image', [$product->product_id, $image->image_id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Thêm hình ảnh mới --}}
        <h4 class="mt-4 font-weight-bold">Thêm hình ảnh mới</h4>
        <form action="{{ route('admin.products.add-images', $product->product_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="images">Chọn hình ảnh</label>
                <input type="file" class="form-control-file" id="images" name="images[]" multiple required>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload"></i> Thêm hình ảnh
            </button>
        </form>
    </div>
</div>
@endsection
