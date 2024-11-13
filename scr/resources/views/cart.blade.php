{{--resources/views/cart.blade.php--}}
@extends('layouts.app')
@section('title', 'Giỏ hàng')
@section('extra_css')
<style>
    img.card-img-top {
        width: 100%;
        height: 100px;
        object-fit: cover;
    }
    
</style>
@endsection
@section('content')
<main class="py-5">
        <div class="container">
            <h1 class="text-center mb-5 mt-4">Giỏ hàng của bạn</h1>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h2 class="card-title mb-4">Sản phẩm trong giỏ hàng</h2>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Sản phẩm</th>
                                            <th scope="col">Giá</th>
                                            <th scope="col">Số lượng</th>
                                            <th scope="col">Tổng</th>
                                            <th scope="col"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ URL::asset('images/default.jpg') }}" alt="Bình gốm hoa văn" class="img-thumbnail me-3" style="width: 100px;">
                                                    <div>
                                                        <h6 class="mb-0">Bình gốm hoa văn</h6>
                                                        <small class="text-muted">Mã SP: BG001</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>500.000 ₫</td>
                                            <td>
                                                <div class="input-group" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary" type="button">-</button>
                                                    <input type="text" class="form-control text-center" value="1">
                                                    <button class="btn btn-outline-secondary" type="button">+</button>
                                                </div>
                                            </td>
                                            <td>500.000 ₫</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ URL::asset('images/default.jpg') }}" alt="Khăn thêu tay" class="img-thumbnail me-3" style="width: 100px;">
                                                    <div>
                                                        <h6 class="mb-0">Khăn thêu tay</h6>
                                                        <small class="text-muted">Mã SP: KT002</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>300.000 ₫</td>
                                            <td>
                                                <div class="input-group" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary" type="button">-</button>
                                                    <input type="text" class="form-control text-center" value="2">
                                                    <button class="btn btn-outline-secondary" type="button">+</button>
                                                </div>
                                            </td>
                                            <td>600.000 ₫</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ URL::asset('images/default.jpg') }}" alt="Vòng tay đá quý" class="img-thumbnail me-3" style="width: 100px;">
                                                    <div>
                                                        <h6 class="mb-0">Vòng tay đá quý</h6>
                                                        <small class="text-muted">Mã SP: VT003</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>450.000 ₫</td>
                                            <td>
                                                <div class="input-group" style="width: 120px;">
                                                    <button class="btn btn-outline-secondary" type="button">-</button>
                                                    <input type="text" class="form-control text-center" value="1">
                                                    <button class="btn btn-outline-secondary" type="button">+</button>
                                                </div>
                                            </td>
                                            <td>450.000 ₫</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="#" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                        </a>
                        <button class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-clockwise me-2"></i>Cập nhật giỏ hàng
                        </button>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4">Tóm tắt đơn hàng</h2>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Tạm tính</span>
                                <span>1.550.000 ₫</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Phí vận chuyển</span>
                                <span>50.000 ₫</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-success">Giảm giá</span>
                                <span class="text-success">-100.000 ₫</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng cộng</strong>
                                <strong>1.500.000 ₫</strong>
                            </div>
                            <div class="mb-3">
                                <label for="couponCode" class="form-label">Mã giảm giá</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="couponCode" placeholder="Nhập mã giảm giá">
                                    <button class="btn btn-outline-secondary" type="button">Áp dụng</button>
                                </div>
                            </div>
                            <a href="#" class="btn btn-primary w-100">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @endsection