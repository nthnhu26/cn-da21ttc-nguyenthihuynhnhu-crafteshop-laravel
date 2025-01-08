@extends('home.layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Chi tiết đơn hàng #{{ $order->order_id }}</h1>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Trạng thái:</strong> {{ $order->status_text }}</p>
            <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Tổng tiền:</strong> {{ number_format($order->final_amount) }}đ</p>

            <h2 class="mt-4">Sản phẩm</h2>
            @foreach($order->items as $item)
            <div class="card mb-2">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <img src="{{ Storage::url($item->product->images->first()->image_url) }}" alt="{{ $item->product->product_name }}" class="img-fluid rounded">
                        </div>
                        <div class="col-md-7">
                            <h5 class="card-title">{{ $item->product->product_name }}</h5>
                            <p class="card-text">Số lượng: {{ $item->quantity }}</p>
                            <p class="card-text">Giá: {{ number_format($item->unit_price) }}đ</p>
                        </div>
                        <div class="col-md-3">
                            @if($order->order_status === 'delivered' && !$item->review)
                                <a href="{{ route('orders.review.form', ['order' => $order, 'product_id' => $item->product_id]) }}" class="btn btn-primary">Đánh giá sản phẩm</a>
                            @elseif($item->review)
                                <span class="badge bg-success">Đã đánh giá</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

