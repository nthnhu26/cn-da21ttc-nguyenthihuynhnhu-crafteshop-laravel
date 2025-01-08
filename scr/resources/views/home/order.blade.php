<div class="container py-4">
        <h1 class="mb-4">Quản Lý Đơn Hàng</h1>

        <!-- Tabs navigation -->
        <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="all-orders-tab" data-bs-toggle="tab" data-bs-target="#all-orders" type="button" role="tab">
                    <i class="bi bi-list-task me-2"></i>Tất Cả Đơn Hàng
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="unpaid-orders-tab" data-bs-toggle="tab" data-bs-target="#unpaid-orders" type="button" role="tab">
                    <i class="bi bi-credit-card me-2"></i>Chưa Thanh Toán
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="shipping-orders-tab" data-bs-toggle="tab" data-bs-target="#shipping-orders" type="button" role="tab">
                    <i class="bi bi-truck me-2"></i>Đang Vận Chuyển
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="review-orders-tab" data-bs-toggle="tab" data-bs-target="#review-orders" type="button" role="tab">
                    <i class="bi bi-star me-2"></i>Chưa Đánh Giá
                </button>
            </li>
        </ul>

        <!-- Tabs content -->
        <div class="tab-content" id="orderTabsContent">
            <!-- Tất Cả Đơn Hàng -->
            <div class="tab-pane fade show active" id="all-orders" role="tabpanel">
                <div class="card order-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-box-seam me-2 status-processing"></i>
                            <span class="fw-bold">Tranh Gốm Hoa Văn Truyền Thống</span>
                        </div>
                        <span class="text-muted">Mã đơn: MN001</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/300x300" class="img-fluid rounded" alt="Sản phẩm">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Ngày đặt:</strong> 15/03/2024</p>
                                        <p><strong>Dự kiến giao:</strong> 25/03/2024</p>
                                        <p>
                                            <strong>Trạng thái:</strong> 
                                            <span class="order-status status-processing">Đang xử lý</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p><strong>Số lượng:</strong> 1</p>
                                        <p><strong>Tổng tiền:</strong> 850.000 đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                            <i class="bi bi-x-circle me-2"></i>Hủy Đơn Hàng
                        </button>
                        <button class="btn btn-outline-secondary">Chi Tiết Đơn Hàng</button>
                    </div>
                </div>
            </div>

            <!-- Chưa Thanh Toán -->
            <div class="tab-pane fade" id="unpaid-orders" role="tabpanel">
                <div class="card order-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-credit-card me-2 status-unpaid"></i>
                            <span class="fw-bold">Bộ Bình Gốm Bát Trang Handmade</span>
                        </div>
                        <span class="text-muted">Mã đơn: MN003</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/300x300" class="img-fluid rounded" alt="Sản phẩm">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Ngày đặt:</strong> 20/03/2024</p>
                                        <p>
                                            <strong>Trạng thái:</strong> 
                                            <span class="order-status status-unpaid">Chưa Thanh Toán</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p><strong>Số lượng:</strong> 1</p>
                                        <p><strong>Tổng tiền:</strong> 1.500.000 đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-primary">
                            <i class="bi bi-credit-card me-2"></i>Thanh Toán Ngay
                        </button>
                        <button class="btn btn-outline-secondary">Chi Tiết Đơn Hàng</button>
                    </div>
                </div>
            </div>

            <!-- Đang Vận Chuyển -->
            <div class="tab-pane fade" id="shipping-orders" role="tabpanel">
                <div class="card order-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-truck me-2 status-shipping"></i>
                            <span class="fw-bold">Tượng Gốm Phong Cách Á Đông</span>
                        </div>
                        <span class="text-muted">Mã đơn: MN002</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/300x300" class="img-fluid rounded" alt="Sản phẩm">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Ngày đặt:</strong> 10/03/2024</p>
                                        <p><strong>Ngày giao dự kiến:</strong> 20/03/2024</p>
                                        <p>
                                            <strong>Trạng thái:</strong> 
                                            <span class="order-status status-shipping">Đang Vận Chuyển</span>
                                        </p>
                                        <p><strong>Mã vận đơn:</strong> VN123456789</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p><strong>Số lượng:</strong> 1</p>
                                        <p><strong>Tổng tiền:</strong> 1.200.000 đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-info">
                            <i class="bi bi-geo-alt me-2"></i>Theo Dõi Vận Chuyển
                        </button>
                        <button class="btn btn-outline-secondary">Chi Tiết Đơn Hàng</button>
                    </div>
                </div>
            </div>

            <!-- Chưa Đánh Giá -->
            <div class="tab-pane fade" id="review-orders" role="tabpanel">
                <div class="card order-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-star me-2 text-warning"></i>
                            <span class="fw-bold">Bình Gốm Đắp Nổi Nghệ Thuật</span>
                        </div>
                        <span class="text-muted">Mã đơn: MN004</span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="https://via.placeholder.com/300x300" class="img-fluid rounded" alt="Sản phẩm">
                            </div>
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Ngày đặt:</strong> 05/03/2024</p>
                                        <p><strong>Ngày giao:</strong> 15/03/2024</p>
                                        <p>
                                            <strong>Trạng thái:</strong> 
                                            <span class="order-status text-success">Đã Giao Hàng</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <p><strong>Số lượng:</strong> 1</p>
                                        <p><strong>Tổng tiền:</strong> 680.000 đ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-warning text-white">
                            <i class="bi bi-star-half me-2"></i>Đánh Giá Ngay
                        </button>
                        <button class="btn btn-outline-secondary">Chi Tiết Đơn Hàng</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Hủy Đơn Hàng -->
        <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cancelOrderModalLabel">Xác Nhận Hủy Đơn Hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn hủy đơn hàng này không? 
                        Hành động này không thể hoàn tác.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Quay Lại</button>
                        <button type="button" class="btn btn-danger" id="confirmCancelBtn">Xác Nhận Hủy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>