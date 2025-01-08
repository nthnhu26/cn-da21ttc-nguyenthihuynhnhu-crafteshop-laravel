@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container-fluid px-4">
    <h1>Chi tiết Sản phẩm</h1>
    <div class="card">
        <div class="card-body">
            <h5>Tên sản phẩm: <b>{{ $product->product_name }}</b></h5>
            <p>Mô tả: {{ $product->description }}</p>
            <p>Mô tả ngắn: {{ $product->short_description }}</p>
            <p>Giá: {{ number_format($product->price, 0, ',', '.') }} VNĐ</p>
            <p>Tồn kho: {{ $product->stock }}</p>
            <p>Danh mục: {{ $product->category->category_name }}</p>
            <p>Trọng lượng: {{ $product->weight }} kg</p>
            <p>Kích thước: {{ $product->dimensions }}</p>
            <p>Chất liệu: {{ $product->material }}</p>
            <p>Xuất xứ: {{ $product->origin }}</p>
            <p>Thời gian bảo hành: {{ $product->warranty_period }} tháng</p>
            <p>Trạng thái: {{ $product->status == 'in_stock' ? 'Còn hàng' : 'Hết hàng' }}</p>
            <div class="row">
                @foreach($product->images as $image)
                <div class="col-md-3 mb-3">
                    <a href="{{ asset('storage/' . $image->image_url) }}" data-lightbox="product-gallery" data-title="Hình ảnh sản phẩm">
                        <img src="{{ asset('storage/' . $image->image_url) }}" alt="Product Image" class="img-fluid">
                    </a>
                    <form action="{{ route('admin.products.delete-image', [$product->product_id, $image->image_id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?')">Xóa</button>
                    </form>
                </div>
                @endforeach
            </div>

            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
        </div>
    </div>
</div>
@endsection