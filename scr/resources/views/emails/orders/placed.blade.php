@extends('emails.layout')

@section('title', 'Chi tiết đơn hàng của bạn')

@section('content')
<div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="color: #28a745;">Đặt hàng thành công!</h2>
        <p style="font-size: 16px;">Cảm ơn bạn đã đặt hàng tại Đồ Thủ Công Mỹ Nghệ.</p>
        <h3 style="color: #007bff;">Mã đơn hàng: {{ $order->order_id }}</h3>
    </div>

    <div style="margin-bottom: 20px;">
        <h4>Thông tin giao hàng</h4>
        <p><strong>Tên khách hàng:</strong> {{ explode(',', $order->address)[0] }}</p>
        <p><strong>Địa chỉ:</strong> {{ trim(substr($order->address, strpos($order->address, ',') + 1)) }}</p>
        <p><strong>Tổng giá trị:</strong> {{ number_format($order->final_amount, 0, ',', '.') }} ₫</p>
    </div>

    <div style="margin-bottom: 20px;">
        <h4>Sản phẩm trong đơn hàng</h4>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th style="text-align: left; border-bottom: 1px solid #ddd; padding: 8px;">Tên sản phẩm</th>
                    <th style="text-align: center; border-bottom: 1px solid #ddd; padding: 8px;">Số lượng</th>
                    <th style="text-align: right; border-bottom: 1px solid #ddd; padding: 8px;">Tổng giá</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                <tr>
                    <td style="padding: 8px;">{{ $item->product->product_name }}</td>
                    <td style="padding: 8px; text-align: center;">{{ $item->quantity }}</td>
                    <td style="padding: 8px; text-align: right;">{{ number_format($item->total_price, 0, ',', '.') }} ₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ $orderUrl }}" style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">Xem chi tiết đơn hàng</a>
    </div>
</div>
@endsection