@extends('admin.layouts.master')
@section('header')
Báo cáo sản phẩm bán chạy</h2>
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
            <form method="GET" class="d-flex gap-2">
                <input type="date" class="form-control" name="start_date" value="{{ $startDate }}">
                <input type="date" class="form-control" name="end_date" value="{{ $endDate }}">
                <button type="submit" class="btn btn-primary">Lọc</button>
            </form>
            <div>
                <a href="{{ route('admin.reports.bestsellers', ['export' => 'excel']) }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
                <a href="{{ route('admin.reports.bestsellers', ['export' => 'pdf']) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Xuất PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng bán</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bestSellers as $product)
                    <tr>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->total_quantity }}</td>
                        <td><strong class="text-success">{{ number_format($product->total_revenue) }} đ</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
