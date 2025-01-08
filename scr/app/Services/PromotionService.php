<?php

namespace App\Services;



use App\Models\Promotion;
use App\Models\Product;
use Carbon\Carbon;

class PromotionService
{
    // public function applyPromotion(Product $product, $quantity = 1)
    // {
    //     $applicablePromotions = Promotion::where('active', true)
    //         ->where('start_date', '<=', Carbon::now())
    //         ->where('end_date', '>=', Carbon::now())
    //         ->where(function ($query) use ($product) {
    //             $query->where('applies_to', 'all_products')
    //                 ->orWhereHas('products', function ($q) use ($product) {
    //                     $q->where('product_id', $product->product_id);
    //                 });
    //         })
    //         ->get();

    //     $bestDiscount = 0;
    //     $appliedPromotion = null;

    //     foreach ($applicablePromotions as $promotion) {
    //         if ($promotion->min_purchase && $product->price * $quantity < $promotion->min_purchase) {
    //             continue;
    //         }

    //         $discount = $this->calculateDiscount($product->price, $promotion, $quantity);

    //         if ($discount > $bestDiscount) {
    //             $bestDiscount = $discount;
    //             $appliedPromotion = $promotion;
    //         }
    //     }

    //     return [
    //         'original_price' => $product->price,
    //         'discounted_price' => $product->price - $bestDiscount,
    //         'discount_amount' => $bestDiscount,
    //         'applied_promotion' => $appliedPromotion,
    //     ];
    // }

    // private function calculateDiscount($price, Promotion $promotion, $quantity)
    // {
    //     $totalPrice = $price * $quantity;

    //     if ($promotion->promo_type === 'percentage') {
    //         $discount = $totalPrice * ($promotion->discount_value / 100);
    //     } else {
    //         $discount = $promotion->discount_value;
    //     }

    //     if ($promotion->max_discount) {
    //         $discount = min($discount, $promotion->max_discount);
    //     }

    //     return $discount;
    // }
}