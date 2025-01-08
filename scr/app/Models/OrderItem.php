<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_item_id';
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount_amount',
        'total_price',
    ];
    public $timestamps = false; // Tắt tự động cập nhật timestamps
    // Mối quan hệ với Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // Mối quan hệ với Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
    public function review()
    {
        return $this->hasOne(Review::class, 'order_item_id', 'order_item_id');
    }

    public function hasReview()
    {
        return $this->review()->exists();
    }
}
