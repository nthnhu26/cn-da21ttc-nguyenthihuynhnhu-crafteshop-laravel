<?php

namespace App\Providers;

use App\Http\Controllers\Home\CartController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $cartController = new CartController();
        //    $cartCount = $cartController->getCartCount();
         //   $view->with('cartCount', $cartCount);
        });
    }
    
}