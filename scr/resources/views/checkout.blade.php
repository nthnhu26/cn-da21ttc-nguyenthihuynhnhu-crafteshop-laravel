{{--resouce_path('views/checkout.blade.php')--}}
@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')
<main class="py-5">
        <div class="container">
            <h1 class="text-center mb-5 mt-4">Thanh toán</h1>
            <div class="row">
                <div class="col-md-8 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4">Thông tin thanh toán</h2>
                            <form>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="firstName" class="form-label">Họ</label>
                                        <input type="text" class="form-control" id="firstName" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="lastName" class="form-label">Tên</label>
                                        <input type="text" class="form-control" id="lastName" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" id="address" required>
                                </div>
                                
                                <hr class="my-4">
                                <h3 class="mb-3">Phương thức thanh toán</h3>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked required>
                                    <label class="form-check-label" for="creditCard">
                                        Thẻ tín dụng
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer" required>
                                    <label class="form-check-label" for="bankTransfer">
                                        Thanh toán qua momo
                                    </label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="cod" required>
                                    <label class="form-check-label" for="cod">
                                        Thanh toán khi nhận hàng
                                    </label>
                                </div>
                                
                                <button class="btn btn-primary btn-lg w-100" type="submit">Đặt hàng</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title mb-4">Tóm tắt đơn hàng</h2>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">Bình gốm hoa văn</h6>
                                        <small class="text-muted">Số lượng: 1</small>
                                    </div>
                                    <span class="text-muted">500.000 ₫</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">Khăn thêu tay</h6>
                                        <small class="text-muted">Số lượng: 2</small>
                                    </div>
                                    <span class="text-muted">600.000 ₫</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">Vòng tay đá quý</h6>
                                        <small class="text-muted">Số lượng: 1</small>
                                    </div>
                                    <span class="text-muted">450.000 ₫</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Tạm tính</span>
                                    <strong>1.550.000 ₫</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Phí vận chuyển</span>
                                    <strong>50.000 ₫</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between bg-light">
                                    <span class="text-success">Giảm giá</span>
                                    <strong class="text-success">-100.000 ₫</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Tổng cộng</span>
                                    <strong>1.500.000 ₫</strong>
                                </li>
                            </ul>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Mã giảm giá">
                                <button class="btn btn-secondary" type="button">Áp dụng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @endsection