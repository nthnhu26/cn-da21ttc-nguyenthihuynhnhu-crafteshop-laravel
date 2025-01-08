@extends('admin.layouts.master')
@section('header')
Báo cáo tồn kho
@endsection

@section('content')
<div class="container-fluid px-4">

    <!-- Nút quay lại giao diện chính -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
        <div>
            <a href="{{ route('admin.reports.inventory', ['export' => 'excel']) }}" class="btn btn-success me-2">
                <i class="fas fa-file-excel me-1"></i> Xuất Excel
            </a>
            <a href="{{ route('admin.reports.inventory', ['export' => 'pdf']) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i> Xuất PDF
            </a>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm bg-primary text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Tổng sản phẩm</h5>
                    <h3>{{ number_format($inventoryStats['total_products']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm bg-success text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Giá trị tồn kho</h5>
                    <h3>{{ number_format($inventoryStats['total_stock_value']) }}đ</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm bg-warning text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Sản phẩm sắp hết</h5>
                    <h3>{{ number_format($inventoryStats['low_stock_products']) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card shadow-sm bg-danger text-white">
                <div class="card-body text-center">
                    <h5 class="card-title">Sản phẩm hết hàng</h5>
                    <h3>{{ number_format($inventoryStats['out_of_stock_products']) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng dữ liệu tồn kho -->
    <div class="card shadow-sm">
        <div class="card-body">
            <table id="inventoryTable" class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Tồn kho</th>
                        <th>Giá</th>
                        <th>Giá trị tồn</th>
                        <th>Đã bán (30 ngày)</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->product_id }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->category->category_name }}</td>
                        <td>{{ number_format($product->stock) }}</td>
                        <td>{{ number_format($product->price) }}đ</td>
                        <td>{{ number_format($product->stock * $product->price) }}đ</td>
                        <td>{{ number_format($product->monthly_sales ?? 0) }}</td>
                        <td>
                            @if($product->stock == 0)
                            <span class="badge bg-danger">Hết hàng</span>
                            @elseif($product->stock < 10)
                            <span class="badge bg-warning">Sắp hết</span>
                            @else
                            <span class="badge bg-success">Còn hàng</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTable Script -->
<script>
    $(document).ready(function() {
        $('#inventoryTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Vietnamese.json'
            },
            responsive: true,
            pageLength: 10,
        });
    });
</script>
@endsection
