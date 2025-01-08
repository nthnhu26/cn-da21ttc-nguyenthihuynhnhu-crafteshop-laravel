@extends('admin.layouts.master')
@section('header')
Báo cáo doanh thu
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
                <button type="submit" class="btn btn-primary me-2">Lọc</button>

            </form>
            <div>
                <a href="{{ route('admin.reports.revenue', ['export' => 'excel']) }}" class="btn btn-success me-2">
                    <i class="fas fa-file-excel me-1"></i> Xuất Excel
                </a>
                <a href="{{ route('admin.reports.revenue', ['export' => 'pdf']) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i> Xuất PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                            <h5>Tổng doanh thu</h5>
                            <h3>{{ number_format($totalRevenue) }} đ</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                            <h5>Tổng đơn hàng</h5>
                            <h3>{{ $totalOrders }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                            <h5>Giá trị đơn trung bình</h5>
                            <h3>{{ number_format($avgOrderValue) }} đ</h3>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-bordered table-hover table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Ngày</th>
                        <th>Số đơn hàng</th>
                        <th>Doanh thu</th>
                        <th>Giảm giá</th>
                        <th>Phí vận chuyển</th>
                        <th>Doanh thu thuần</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenues as $revenue)
                    <tr>
                        <td>{{ $revenue->date }}</td>
                        <td>{{ $revenue->total_orders }}</td>
                        <td>{{ number_format($revenue->total_revenue) }} đ</td>
                        <td>{{ number_format($revenue->total_discount) }} đ</td>
                        <td>{{ number_format($revenue->total_shipping) }} đ</td>
                        <td><strong class="text-success">{{ number_format($revenue->total_revenue - $revenue->total_discount) }} đ</strong></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection