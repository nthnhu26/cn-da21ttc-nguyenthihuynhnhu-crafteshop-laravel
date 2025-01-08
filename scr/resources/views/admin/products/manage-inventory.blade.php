@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h3 class="m-0 font-weight-bold text-primary">{{ $product->product_name }}</h3>
        <a href="{{ route('admin.products.index') }}" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Quay lại</a>
    </div>
    <div class="card-body">
        <p>Tồn kho hiện tại: {{ $product->stock }}</p>

        <form action="{{ route('admin.products.update-inventory', $product->product_id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="quantity_change">Thay đổi số lượng</label>
                <input type="number" class="form-control" id="quantity_change" name="quantity_change" required>
            </div>
            <div class="form-group">
                <label for="reason">Lý do</label>
                <input type="text" class="form-control" id="reason" name="reason" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập nhật tồn kho</button>
        </form>

        <h2 class="mt-4">Lịch sử thay đổi tồn kho</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>Số lượng thay đổi</th>
                    <th>Lý do</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->inventoryChanges as $change)
                <tr>
                    <td>{{ $change->date }}</td>
                    <td>{{ $change->quantity_change }}</td>
                    <td>{{ $change->reason }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection