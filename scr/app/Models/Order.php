<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';
    protected $fillable = [
        'user_id',
        'address',
        'shipping_fee',
        'customer_note',
        'total_amount',
        'discount',
        'final_amount',
        'order_status',
    ];
    protected $casts = [
        'address' => 'array',
    ];
    // Mối quan hệ với bảng OrderItems
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    // Mối quan hệ với bảng Payments
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    // Mối quan hệ với User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }



    // Tính tổng đơn hàng
    public function calculateTotal()
    {
        return $this->items->sum(fn($item) => $item->total_price) + $this->shipping_fee - $this->discount;
    }

    public function canBeCancelled()
    {
        return in_array($this->order_status, ['pending', 'processing']);
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
        ];

        return $statuses[$this->order_status] ?? 'Không xác định';
    }
    public function canBeReviewed()
    {
        return $this->order_status === 'delivered' && !$this->is_reviewed;
    }
}
