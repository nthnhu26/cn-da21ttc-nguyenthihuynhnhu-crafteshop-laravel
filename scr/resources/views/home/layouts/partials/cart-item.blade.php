<div class="dropdown-item">
    <div class="d-flex justify-content-between">
        <span>{{ $details['name'] }} (x{{ $details['quantity'] }})</span>
        <span>{{ number_format($details['price'] * $details['quantity']) }}đ</span>
    </div>
</div>
