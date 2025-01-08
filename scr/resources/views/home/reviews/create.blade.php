@extends('home.layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Đánh Giá Sản Phẩm</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <img src="{{ $product->image_url ?? 'placeholder.jpg' }}" 
                     class="img-thumbnail me-3" 
                     style="max-width: 80px;" 
                     alt="{{ $product->name }}">
                {{ $product->name }}
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('reviews.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                <div class="mb-3">
                    <label class="form-label">Đánh Giá Sao</label>
                    <div class="star-rating">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                   class="star-input" required>
                            <label for="star{{ $i }}" class="star-label">
                                <i class="bi bi-star{{ $i <= old('rating', 0) ? '-fill text-warning' : '' }}"></i>
                            </label>
                        @endfor
                    </div>
                    @error('rating')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu Đề Đánh Giá (Tùy Chọn)</label>
                    <input type="text" class="form-control" id="title" name="title" 
                           value="{{ old('title') }}" 
                           placeholder="Nhập tiêu đề đánh giá">
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Nội Dung Đánh Giá</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" 
                              placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm" 
                              required>{{ old('comment') }}</textarea>
                    @error('comment')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="media_urls" class="form-label">Hình Ảnh Đánh Giá (Tối Đa 5 Ảnh)</label>
                    <input type="file" class="form-control" id="media_urls" name="media_urls[]" 
                           accept="image/jpeg,image/png,image/jpg" 
                           multiple>
                    @error('media_urls')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-star-half me-2"></i>Gửi Đánh Giá
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
    }
    .star-input {
        display: none;
    }
    .star-label {
        cursor: pointer;
        font-size: 2rem;
        color: #ddd;
        margin: 0 5px;
    }
    .star-input:checked ~ .star-label,
    .star-input:checked ~ .star-label ~ .star-label {
        color: #ffc107;
    }
</style>
@endpush
@endsection
