@extends('home.layouts.app')

@section('content')
<div class="container py-5">
    <!-- Success Message -->
    <div class="text-center">
        <i class="bi bi-check-circle-fill text-success display-1"></i>
        <h1 class="mt-4">Đặt hàng thành công!</h1>
        <p class="lead">Cảm ơn bạn đã đặt hàng. Mã đơn hàng của bạn là:</p>
        <h4 class="fw-bold text-primary">{{ $order->order_id }}</h4>
    </div>

    <!-- Order Summary -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="fw-bold text-center text-uppercase mb-4">Tổng giá trị đơn hàng</h3>
                    <p class="text-center fs-4 fw-bold text-success">{{ number_format($order->final_amount, 0, ',', '.') }} VNĐ</p>

                    <div class="row text-center">
                        <div class="col-md-4">
                            <h5>Thông tin giao hàng</h5>
                            <p>{{ $order->address }}</p>
                        </div>
                        <div class="col-md-4">
                            <h5>Giảm giá</h5>
                            <p>{{ number_format($order->discount, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div class="col-md-4">
                            <h5>Phí vận chuyển</h5>
                            <p>{{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product List -->
    <div class="mt-5">
        <h3 class="text-center text-uppercase mb-4">Danh sách sản phẩm</h3>
        @if($order->items->isNotEmpty())
        <div class="table-responsive">
            <table class="table table-bordered table-striped mx-auto" style="max-width: 800px;">
                <thead class="table-primary text-center">
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                        <th>Tổng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->product_name }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-end">{{ number_format($item->product->price, 0, ',', '.') }} VNĐ</td>
                        <td class="text-end">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p class="text-center text-muted">Không có sản phẩm nào trong đơn hàng.</p>
        @endif
    </div>

    <!-- Continue Shopping Button -->
    <div class="text-center mt-5">
        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
        </a>
    </div>
</div>
@endsection
