<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Province;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Tính số lượng sản phẩm
            $cartItemCount = 0;
            $cartTotal = 0;

            if (Auth::check()) {
                // Nếu đã đăng nhập
                $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
            } else {
                // Nếu chưa đăng nhập, sử dụng session
                $cart = Cart::where('session_id', session()->getId())->with('items.product')->first();
            }

            if ($cart && $cart->items) {
                $cartItemCount = $cart->items->sum('quantity');
                $cartTotal = $cart->items->sum(function($item) {
                    return $item->quantity * $item->product->price;
                });
            }

            // Chia sẻ dữ liệu cho tất cả view
            $view->with([
                'guestCartCount' => $cartItemCount,
                'cartCount' => $cartItemCount,
                'total' => $cartTotal
            ]);
        });
     
    }
}
