<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';
    protected $primaryKey = 'promo_id';

    // Các hằng số định nghĩa loại khuyến mãi
    const TYPE_PERCENT = 'percent'; // Giảm giá theo % cho sản phẩm
    const TYPE_FIXED = 'fixed';     // Giảm giá cố định cho đơn hàng

    protected $fillable = [
        'name',
        'type',
        'value',
        'start_date',
        'end_date',
        'min_amount',
        'code',
        'max_quantity',
        'usage_per_code'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'min_amount' => 'float',
        'value' => 'float',
        'max_quantity' => 'integer',
        'usage_per_code' => 'integer'
    ];

    // Chỉ products vì percent chỉ áp dụng cho sản phẩm
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_products', 'promo_id', 'product_id');
    }

    public function getStatusAttribute()
    {
        $now = Carbon::now();

        if ($this->start_date > $now) {
            return 'Sắp diễn ra';
        } elseif ($this->start_date <= $now && $this->end_date >= $now) {
            return 'Đang diễn ra';
        } else {
            return 'Đã kết thúc';
        }
    }

    public function isActive()
    {
        $now = Carbon::now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    // Tính giảm giá cho sản phẩm (chỉ áp dụng cho loại percent)
    public function calculateProductDiscount($productPrice)
    {
        if (!$this->isActive() || $this->type !== self::TYPE_PERCENT) {
            return 0;
        }

        return round($productPrice * ($this->value / 100), 2);
    }

    // Tính giảm giá cho đơn hàng (chỉ áp dụng cho loại fixed)
    public function calculateOrderDiscount($orderTotal)
    {
        if (!$this->isActive() || $this->type !== self::TYPE_FIXED) {
            return 0;
        }

        if ($this->min_amount && $orderTotal < $this->min_amount) {
            return 0;
        }

        return min($this->value, $orderTotal);
    }

    public function getFormattedDiscountAttribute()
    {
        if ($this->type === self::TYPE_PERCENT) {
            return $this->value . '%';
        }
        return number_format($this->value, 0, ',', '.') . 'đ';
    }

    public function getMinAmountFormattedAttribute()
    {
        return $this->min_amount ? number_format($this->min_amount, 0, ',', '.') . 'đ' : 'Không giới hạn';
    }
}