<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_name',
        'description',
        'short_description',
        'price',
        'stock',
        'category_id',
        'weight',
        'dimensions',
        'material',
        'origin',
        'warranty_period',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'weight' => 'decimal:2',
        'stock' => 'integer',
        'warranty_period' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }
    public function promotion()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_products', 'product_id', 'promo_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function inventoryChanges()
    {
        return $this->hasMany(InventoryChange::class, 'product_id', 'product_id');
    }

    public function updateStock($quantity, $reason)
    {
        $this->stock += $quantity;
        $this->save();
        $this->updateStatus();
        $this->inventoryChanges()->create([
            'quantity_change' => $quantity,
            'reason' => $reason,
        ]);
    }

    protected function updateStatus()
    {
        if ($this->stock > 5) {
            $this->status = 'in_stock'; // Còn hàng
        } elseif ($this->stock <= 5 && $this->stock > 0) {
            $this->status = 'low_stock'; // Tồn kho thấp
        } elseif ($this->stock == 0) {
            $this->status = 'out_of_stock'; // Hết hàng
        }

        $this->saveQuietly(); // Lưu mà không kích hoạt sự kiện
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'product_id')->where('status', 'visible');
    }

    public function activePromotions()
    {
        $now = now();
        return $this->promotion->filter(function ($promotion) use ($now) {
            return $promotion->isActive();
        });
    }

    public function getDiscountedPriceAttribute()
    {
        $originalPrice = $this->price;
        $activePromotions = $this->activePromotions();

        if ($activePromotions->isEmpty()) {
            return $originalPrice;
        }

        $maxDiscount = $activePromotions->max(function ($promotion) use ($originalPrice) {
            if ($promotion->type === Promotion::TYPE_PERCENT) {
                return $promotion->calculateProductDiscount($originalPrice);
            }
            return 0; // Fixed promotions are not applicable to individual products
        });

        return max($originalPrice - $maxDiscount, 0);
    }

    public function getDiscountedPrice()
    {
        $activePromotion = $this->activePromotions()->first();
        if ($activePromotion) {
            return $activePromotion->calculateProductDiscount($this->price);
        }
        return $this->price;
    }
    public function orderitems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }
}
