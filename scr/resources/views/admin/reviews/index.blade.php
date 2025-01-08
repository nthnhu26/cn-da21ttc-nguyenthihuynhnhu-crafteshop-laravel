@extends('admin.layouts.master')
@section('header')
Danh sách đánh giá
@endsection
@section('content')
<div class="container-fluid px-4">
    <div class="card shadow mb-4">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sản phẩm</th>
                            <th>Khách hàng</th>
                            <th>Xếp hạng</th>
                            <th>Bình luận</th>
                            <th>Trạng thái</th>
                            <th>Lý do ẩn</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a href="{{ route('products.show', $review->product->product_id) }}" target="_blank">
                                    {{ $review->product->product_name }}
                                </a>
                            </td>
                            <td>{{ $review->user->name }}</td>
                            <td>{{ $review->rating }} / 5</td>
                            <td>{{ $review->comment }}</td>
                            <td class="text-white">
                                @if ($review->status == 'visible')
                                <span class="badge bg-success">Hiển thị</span>
                                @else
                                <span class="badge bg-danger">Ẩn</span>
                                @endif
                            </td>
                            <td>
                                @if ($review->hide_reason)
                               {{ $review->hide_reason }}
                                @else
                                <em class="text-muted">Không có</em>
                                @endif
                            </td>
                            <td>
                                @if ($review->status == 'visible')
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#hideReviewModal" data-review-id="{{ $review->review_id }}">Ẩn</button>
                                @else
                                <form action="{{ route('admin.reviews.show', $review->review_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Hiển thị</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có đánh giá nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    <!-- Modal -->
                    <div class="modal fade" id="hideReviewModal" tabindex="-1" aria-labelledby="hideReviewModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form id="hideReviewForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="hideReviewModalLabel">Ẩn đánh giá</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="hideReason" class="form-label">Lý do ẩn</label>
                                            <textarea id="hideReason" name="hide_reason" class="form-control" rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">Ẩn</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hideReviewModal = document.getElementById('hideReviewModal');
        const hideReviewForm = document.getElementById('hideReviewForm');

        hideReviewModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const reviewId = button.getAttribute('data-review-id');
            const actionUrl = "{{ route('admin.reviews.hide', ':id') }}".replace(':id', reviewId);

            hideReviewForm.action = actionUrl;
        });
    });
</script>
@endsection