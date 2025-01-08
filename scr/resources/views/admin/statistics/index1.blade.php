{{-- resources/views/admin/statistics/index.blade.php --}}
@extends('admin.layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Lọc Dữ Liệu
        </div>
        <div class="card-body">
            <form id="dateFilterForm" class="row g-3 align-items-center">
                @csrf <!-- Thêm CSRF token -->
                <div class="col-auto">
                    <label for="filterType" class="col-form-label">Loại Lọc:</label>
                </div>
                <div class="col-auto">
                    <select id="filterType" name="filter_type" class="form-select">
                        <option value="day">Ngày</option>
                        <option value="week">Tuần</option>
                        <option value="month">Tháng</option>
                        <option value="custom">Tùy chỉnh</option>
                    </select>
                </div>
                <div class="col-auto custom-date-range" style="display: none;">
                    <label for="startDate" class="col-form-label">Từ Ngày:</label>
                </div>
                <div class="col-auto custom-date-range" style="display: none;">
                    <input type="date" id="startDate" name="start_date" class="form-control">
                </div>
                <div class="col-auto custom-date-range" style="display: none;">
                    <label for="endDate" class="col-form-label">Đến Ngày:</label>
                </div>
                <div class="col-auto custom-date-range" style="display: none;">
                    <input type="date" id="endDate" name="end_date" class="form-control">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Áp Dụng</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Thống kê tồn kho --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-warehouse me-1"></i>
            Thống kê tồn kho
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalProducts }} </h3>
                            <p>Tổng số sản phẩm</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $outOfStock }}</h3>
                            <p>Sản phẩm hết hàng</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $lowStock ?? 0 }}</h3>
                            <p>Sản Phẩm Sắp Hết Hàng</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng hiện tại</th>
                            <th>Trạng thái</th>
                            <th>Tổng thay đổi</th>
                            <th>Lịch sử thay đổi</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                @if($product->stock <= 0)
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded">Hết hàng</span>
                                    @elseif($product->stock <= 10)
                                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded">Sắp hết hàng</span>
                                        @else
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded">Còn hàng</span>
                                        @endif
                            </td>

                            <td>{{ $product->total_changes }}</td>
                            <td>{{ $product->changes_count }} changes</td>
                            <td>
                                <a href="{{ route('admin.products.manage-inventory', $product->product_id) }}" class="btn btn-sm btn-secondary hover-icon-btn">
                                    <i class="fas fa-eye"></i>
                                    <span class="tooltip-text">Xem chi tiết</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    .small-box {
        border-radius: 4px;
        position: relative;
        display: block;
        padding: 5px 0;
        margin-bottom: 20px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
    }

    .small-box>.inner {
        padding: 10px;
    }

    .small-box h3 {
        font-size: 1.8rem;
        font-weight: bold;
        margin: 0 0 10px 0;
        white-space: nowrap;
        padding: 0;
        color: #fff;
    }

    .small-box p {
        color: #fff;
        margin-bottom: 0;
    }

    .small-box .icon {
        position: absolute;
        top: 5px;
        right: 10px;
        z-index: 0;
        font-size: 70px;
        color: rgba(0, 0, 0, 0.15);
    }
</style>
@endsection