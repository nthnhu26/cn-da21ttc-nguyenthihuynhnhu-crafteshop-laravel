<!-- orders/index.blade.php -->
@section('title', 'Quản Lý Đơn Hàng')
@extends('home.layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Quản Lý Đơn Hàng</h1>

    <!-- Tabs navigation -->
    <ul class="nav nav-pills nav-fill small-tabs mb-3" id="orderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="all-orders-tab" data-bs-toggle="tab" data-bs-target="#all-orders" type="button" role="tab">
                <i class="bi bi-list-task me-1"></i>Tất cả
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pending-orders-tab" data-bs-toggle="tab" data-bs-target="#pending-orders" type="button" role="tab">
                <i class="bi bi-hourglass-split me-1"></i>Chờ xác nhận
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="unpaid-orders-tab" data-bs-toggle="tab" data-bs-target="#unpaid-orders" type="button" role="tab">
                <i class="bi bi-credit-card me-1"></i>Chưa thanh toán
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="processing-orders-tab" data-bs-toggle="tab" data-bs-target="#processing-orders" type="button" role="tab">
                <i class="bi bi-box-seam me-1"></i>Đang xử lý
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="shipping-orders-tab" data-bs-toggle="tab" data-bs-target="#shipping-orders" type="button" role="tab">
                <i class="bi bi-truck me-1"></i>Đang giao
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="delivered-orders-tab" data-bs-toggle="tab" data-bs-target="#delivered-orders" type="button" role="tab">
                <i class="bi bi-check-circle me-1"></i>Hoàn thành
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="cancelled-orders-tab" data-bs-toggle="tab" data-bs-target="#cancelled-orders" type="button" role="tab">
                <i class="bi bi-x-circle me-1"></i>Đã hủy
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="review-orders-tab" data-bs-toggle="tab" data-bs-target="#review-orders" type="button" role="tab">
                <i class="bi bi-star me-1"></i>Cần đánh giá
            </button>
        </li>
    </ul>

    <!-- Tabs content -->
    <div class="tab-content" id="orderTabsContent">
        @foreach(['all' => 'Tất cả đơn hàng',
        'pending' => 'Chờ xác nhận',
        'unpaid' => 'Chưa thanh toán',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang vận chuyển',
        'delivered' => 'Đã hoàn thành',
        'cancelled' => 'Đã hủy',
        'review' => 'Chưa đánh giá'] as $status => $title)

        <div class="tab-pane fade {{ $status == 'all' ? 'show active' : '' }}" id="{{ $status }}-orders" role="tabpanel">
            @php
            $filteredOrders = $status === 'all' ? $orders : $orders->filter(function($order) use ($status) {
            if ($status === 'review') {
            // Lọc các đơn hàng có ít nhất một sản phẩm chưa được đánh giá
            return $order->order_status === 'delivered' && $order->items->contains(function($item) {
            return !$item->review;
            });
            }
            return $order->order_status === $status;
            });
            @endphp

            @if($filteredOrders->isNotEmpty())
            @foreach($filteredOrders as $order)
            <!-- Hiển thị thông tin đơn hàng -->
            <div class="card order-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="bi bi-box-seam me-2 status-{{ $order->order_status }}"></i>
                        <span class="fw-bold">{{ $order->order_id }}</span>
                    </div>
                    <span class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</span>
                </div>
                <div class="card-body">
                    <!-- Hiển thị các item trong đơn hàng -->
                    @foreach($order->items as $item)
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <img src="{{ Storage::url($item->product->images->first()->image_url) }}" class="img-fluid rounded" alt="{{ $item->product->product_name }}">
                        </div>
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>{{ $item->product->product_name }}</h5>
                                    <p class="text-muted">Số lượng: {{ $item->quantity }}</p>
                                    <p><strong>Trạng thái:</strong> {{ $order->status_text }}</p>
                                    <p><strong>Tổng tiền:</strong> {{ number_format($order->final_amount) }}đ</p>
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Chi tiết</a>
                                    @if($order->order_status === 'pending')
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <button type="button" onclick="confirmCancelOrder(this.form)" class="btn btn-danger">
                                            Hủy đơn hàng
                                        </button>
                                    </form>
                                    @endif
                                    @if($order->order_status === 'delivered' && !$item->review)
                                    <a href="{{ route('orders.review.form', ['order' => $order, 'product_id' => $item->product_id]) }}" class="btn btn-primary">Đánh giá sản phẩm</a>
                                    @elseif($item->review)
                                    @if($item->review)
                                    <a href="{{ route('reviews.view', ['orderId' => $order->order_id, 'productId' => $item->product_id]) }}" class="btn btn-success">Đã đánh giá</a>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            @else
            <!-- Hiển thị thông báo không có đơn hàng -->
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted"></i>
                <p class="mt-3">Không có đơn hàng nào trong mục này</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>

<style>
    /* Existing styles */
    .order-card {
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .order-status {
        font-weight: bold;
    }

    .status-pending {
        color: #ffc107;
    }

    .status-unpaid {
        color: #6c757d;
    }

    .status-processing {
        color: #0dcaf0;
    }

    .status-shipping {
        color: #0d6efd;
    }

    .status-delivered {
        color: #198754;
    }

    .status-cancelled {
        color: #dc3545;
    }

    /* New styles for tabs */
    .small-tabs {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 0.5rem;
        gap: 0.5rem;
    }

    .small-tabs .nav-item {
        margin: 0 2px;
    }

    .small-tabs .nav-link {
        font-size: 1.2rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.25rem;
        color: #495057;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .small-tabs .nav-link i {
        font-size: 1.15rem;
    }

    .small-tabs .nav-link:hover {
        background-color: rgba(255, 193, 69, 0.1);
        color: #000;
    }

    .small-tabs .nav-link.active {
        background-color: #FFC145 !important;
        color: #000 !important;
        border: none;
        font-weight: 500;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .small-tabs {
            flex-wrap: nowrap;
            overflow-x: auto;
            padding: 0.5rem;
        }

        .small-tabs::-webkit-scrollbar {
            height: 4px;
        }

        .small-tabs::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .small-tabs::-webkit-scrollbar-thumb {
            background: #FFC145;
            border-radius: 4px;
        }
    }
</style>


@endsection