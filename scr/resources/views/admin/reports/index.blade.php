@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container-fluid px-4">

    <div class="row mt-4">
        <!-- Báo cáo doanh thu -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow border-0 mb-4">
                <div class="card-body text-center">
                    <i class="fas fa-dollar-sign fa-3x text-primary mb-3"></i>
                    <h5 class="card-title">Báo cáo doanh thu</h5>
                </div>
                <div class="card-footer text-center bg-primary text-white">
                    <a class="text-white fw-bold text-decoration-none" href="{{ route('admin.reports.revenue') }}">
                        Xem chi tiết <i class="fas fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Báo cáo tồn kho -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow border-0 mb-4">
                <div class="card-body text-center">
                    <i class="fas fa-boxes fa-3x text-success mb-3"></i>
                    <h5 class="card-title">Báo cáo tồn kho</h5>
                </div>
                <div class="card-footer text-center bg-success text-white">
                    <a class="text-white fw-bold text-decoration-none" href="{{ route('admin.reports.inventory') }}">
                        Xem chi tiết <i class="fas fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Báo cáo sản phẩm bán chạy -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow border-0 mb-4">
                <div class="card-body text-center">
                    <i class="fas fa-fire fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Sản phẩm bán chạy</h5>
                </div>
                <div class="card-footer text-center bg-warning text-white">
                    <a class="text-white fw-bold text-decoration-none" href="{{ route('admin.reports.bestsellers') }}">
                        Xem chi tiết <i class="fas fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Báo cáo theo danh mục -->
        <div class="col-xl-3 col-md-6">
            <div class="card shadow border-0 mb-4">
                <div class="card-body text-center">
                    <i class="fas fa-tags fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Báo cáo theo danh mục</h5>
                </div>
                <div class="card-footer text-center bg-info text-white">
                    <a class="text-white fw-bold text-decoration-none" href="{{ route('admin.reports.category') }}">
                        Xem chi tiết <i class="fas fa-angle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
