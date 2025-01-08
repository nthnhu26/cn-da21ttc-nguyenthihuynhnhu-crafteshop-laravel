@extends('home.layouts.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Chi tiết đánh giá</h4>
            @if(Carbon\Carbon::parse($review->created_at)->diffInHours(now()) <= 24)
                <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editReviewModal">
                    Chỉnh sửa
                </button>
                <form action="{{ route('reviews.delete', $review) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                        Xóa
                    </button>
                </form>
        </div>
        @endif
    </div>
    <div class="card-body">
        <h5>{{ $review->title }}</h5>
        <div class="rating-display mb-3">
            @for($i = 1; $i <= 5; $i++)
                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning"></i>
                @endfor
        </div>
        <p>{{ $review->comment }}</p>
        @if($review->media_urls)
        <div class="review-images">
            @foreach(json_decode($review->media_urls) as $url)
            <img src="{{ $url }}" alt="Review image" class="img-thumbnail" style="max-width: 200px">
            @endforeach

        </div>
        @endif
        <small class="text-muted">Đăng vào: {{ $review->created_at->format('d/m/Y H:i') }}</small>
    </div>
</div>
</div>

<!-- Edit Review Modal -->
<div class="modal fade" id="editReviewModal" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('reviews.edit', $review) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa đánh giá</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $review->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Đánh giá</label>
                        <div class="rating">
                            @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" {{ $review->rating == $i ? 'checked' : '' }} required>
                            <label for="star{{ $i }}"></label>
                            @endfor
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Nhận xét</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required>{{ $review->comment }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="images" class="form-label">Hình ảnh mới (tải lên sẽ thay thế ảnh cũ)</label>
                        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                        <small class="text-muted">Tối đa 5 ảnh, mỗi ảnh không quá 2MB</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }

    .rating input {
        display: none;
    }

    .rating label {
        cursor: pointer;
        width: 30px;
        height: 30px;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 24 24'%3e%3cpath d='M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z' fill='%23d3d3d3'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: center;
        background-size: 30px;
    }

    .rating label:hover,
    .rating label:hover~label,
    .rating input:checked~label {
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 24 24'%3e%3cpath d='M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z' fill='%23ffc107'/%3e%3c/svg%3e");
    }
</style>
@endsection