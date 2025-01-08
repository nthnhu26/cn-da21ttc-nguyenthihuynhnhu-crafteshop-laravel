<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryChange extends Model
{
    use HasFactory;

    protected $primaryKey = 'inventory_change_id';

    protected $fillable = [
        'product_id',
        'quantity_change',
        'reason',
    ];
    public $timestamps = false;
    protected $casts = [
        'created_at' => 'datetime',
        'quantity_change' => 'integer'
    ];

  
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    public static function recordChange($productId, $quantityChange, $reason)
    {
        return static::create([
            'product_id' => $productId,
            'quantity_change' => $quantityChange,
            'reason' => $reason,
            'created_at' => now()
        ]);
    }
}
