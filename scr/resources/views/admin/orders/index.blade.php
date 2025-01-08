@extends('admin.layouts.master')
@section('header')
Danh sách đơn hàng
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">

        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipped" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang vận chuyển</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Bị hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Lọc</button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Đặt lại</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->user->name }} ({{ $order->user->email }})</td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }} đ</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                $statuses = [
                                'pending' => 'Chờ xử lý',
                                'processing' => 'Đang xử lý',
                                'shipping' => 'Đang vận chuyển',
                                'delivered' => 'Đã giao hàng',
                                'cancelled' => 'Đã hủy',
                                ];
                                @endphp
                                <span class="badge bg-{{ $order->order_status == 'pending' ? 'warning' : ($order->order_status == 'delivered' ? 'success' : 'primary') }} text-white">
                                    {{ $statuses[$order->order_status] ?? 'Không xác định' }}
                                </span>

                            </td>
                            <td>
                                @if (in_array($order->order_status, ['cancelled', 'delivered']))
                                <span class="text-muted">Không thể cập nhật</span>
                                @else
                                <form action="{{ route('admin.orders.update-status', $order->order_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm d-inline-block w-auto"
                                        onchange="this.form.submit()">
                                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                        <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="shipping" {{ $order->order_status == 'shipping' ? 'selected' : '' }}>Đang vận chuyển</option>
                                        <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                    </select>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection