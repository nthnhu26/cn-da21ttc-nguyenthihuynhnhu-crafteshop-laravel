@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h4>Chi Tiết Khuyến Mãi: {{ $promotion->name }}</h4>
    </div>
    <div class="card-body">
        <p><strong>Loại:</strong> {{ $promotion->type }}</p>
        <p><strong>Giá Trị:</strong> {{ $promotion->value }}</p>
        <p><strong>Ngày Bắt Đầu:</strong> {{ $promotion->start_date }}</p>
        <p><strong>Ngày Kết Thúc:</strong> {{ $promotion->end_date }}</p>
        <p><strong>Mã Giảm Giá:</strong> {{ $promotion->code }}</p>
        <p><strong>Số Tiền Tối Thiểu:</strong> {{ $promotion->min_amount }}</p>
        <p><strong>Trạng Thái:</strong> {{ $promotion->status }}</p>

        <h5>Sản Phẩm Áp Dụng</h5>
        <ul>
            @foreach($promotion->products as $product)
            <li>{{ $product->product->name }}</li>
            @endforeach
        </ul>

        <h5>Danh Mục Áp Dụng</h5>
        <ul>
            @foreach($promotion->products->whereNotNull('category_id') as $category)
            <li>{{ $category->category->name }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
