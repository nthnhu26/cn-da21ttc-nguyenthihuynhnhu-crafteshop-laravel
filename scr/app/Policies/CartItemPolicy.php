<?php

namespace App\Policies;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartItemPolicy
{
    use HandlesAuthorization;

    public function update(User $user, CartItem $cartItem)
    {
        return $cartItem->cart->user_id === $user->id ||
               $cartItem->cart->session_id === session()->get('cart_session_id');
    }

    public function delete(User $user, CartItem $cartItem)
    {
        return $cartItem->cart->user_id === $user->id ||
               $cartItem->cart->session_id === session()->get('cart_session_id');
    }
}