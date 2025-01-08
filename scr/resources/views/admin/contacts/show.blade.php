@extends('admin.layouts.master')
@section('header')
Báo cáo thống kê
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Chi tiết Liên hệ</h4>
            <div class="mb-4">
                <strong>Họ và tên:</strong> {{ $contact->name }}
            </div>
            <div class="mb-4">
                <strong>Email:</strong> {{ $contact->email }}
            </div>
            <div class="mb-4">
                <strong>Số điện thoại:</strong> {{ $contact->phone }}
            </div>
            <div class="mb-4">
                <strong>Nội dung:</strong>
                <p>{{ $contact->message }}</p>
            </div>
            <div class="mb-4">
                <strong>Trạng thái:</strong> {{ $contact->status==='pending'?'Đang chờ phản hồi':'Đã phản hồi' }}
            </div>

            @if($contact->status !== 'resolved')
            <form action="{{ route('admin.contacts.respond', $contact) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="response" class="form-label">Phản hồi</label>
                    <textarea class="form-control" id="response" name="response" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
            </form>
            @else
            <div class="mb-4">
                <strong>Phản hồi:</strong>
                <p>{{ $contact->response }}</p>
            </div>
            <div class="mb-4">
                <strong>Thời gian phản hồi:</strong> {{ $contact->updated_at->diffForHumans() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection