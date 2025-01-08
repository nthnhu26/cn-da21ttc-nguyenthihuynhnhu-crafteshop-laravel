@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="container">
    <h1>Chỉnh sửa Banner</h1>

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $banner->title) }}" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $banner->description) }}</textarea>
        </div>
        <div class="form-group">
            <label for="image">Hình ảnh</label>
            <input type="file" class="form-control-file" id="image" name="image">
            @if($banner->image_url)
                <img src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}" style="max-width: 200px; margin-top: 10px;">
            @endif
        </div>
        <div class="form-group">
            <label for="link">Liên kết</label>
            <input type="text" class="form-control" id="link" name="link" value="{{ old('link', $banner->link) }}">
        </div>
        <div class="form-group">
            <label for="start_datetime">Thời gian bắt đầu</label>
            <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" value="{{ old('start_datetime', $banner->start_datetime->format('Y-m-d\TH:i')) }}" required>
        </div>
        <div class="form-group">
            <label for="end_datetime">Thời gian kết thúc</label>
            <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" value="{{ old('end_datetime', $banner->end_datetime->format('Y-m-d\TH:i')) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật Banner</button>
    </form>
</div>
@endsection