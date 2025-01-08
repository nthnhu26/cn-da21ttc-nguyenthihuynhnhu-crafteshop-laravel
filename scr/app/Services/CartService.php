<?php

namespace App\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartService
{
    private function getCurrentCart($createIfNotExist = false)
    {
        $cart = Auth::check()
            ? Cart::where('user_id', Auth::id())->first()
            : Cart::where('session_id', session()->getId())->first();

        if (!$cart && $createIfNotExist) {
            $cart = new Cart();
            if (Auth::check()) {
                $cart->user_id = Auth::id();
            } else {
                $cart->session_id = session()->getId();
            }
            $cart->save();
        }

        return $cart;
    }
    public function mergeGuestCartToUserCart($sessionId, $user)
    {
        DB::transaction(function () use ($sessionId, $user) {
            $guestCart = Cart::where('session_id', $sessionId)->first();
            $userCart = Cart::firstOrCreate(['user_id' => $user->user_id]);

            if ($guestCart) {
                foreach ($guestCart->items as $item) {
                    $userCart->items()->updateOrCreate(
                        ['product_id' => $item->product_id],
                        ['quantity' => DB::raw("quantity + {$item->quantity}")]
                    );
                }

                $guestCart->delete();
            }

            $userCart->session_id = session()->getId();
            $userCart->save();
        });
    }
}