@extends('emails.layout')

@section('title', 'Phản Hồi Liên Hệ')

@section('content')
<div class="response-content">
    <div class="customer-name">
        Xin chào: {{ $contact->name }}
    </div>

    <p>Cảm ơn bạn đã liên hệ với chúng tôi.</p>

    <div class="message-section">
        <h3>Nội dung bạn đã gửi:</h3>
        <p style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
            {{ $contact->message }}
        </p>
    </div>

    <div class="response-section">
        <h3>Phản hồi của chúng tôi:</h3>
        <p style="background: #e8f4ff; padding: 15px; border-radius: 5px;">
            {{ $responseMessage }}
        </p>
    </div>
</div>
@endsection