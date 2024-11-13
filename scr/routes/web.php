<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/products', function () {
    return view('products');
})->name('products');
Route::get('/productdetails', function () {
    return view('productdetails');
})->name('productdetails');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Add other routes as needed
Route::get('/return-policy', function () {
    return view('return-policy');
})->name('return-policy');

Route::get('/buying-guide', function () {
    return view('buying-guide');
})->name('buying-guide');

Route::get('/faq', function () {
    return view('faq');
})->name('faq');

Route::post('/newsletter/subscribe', function () {
    // Add newsletter subscription logic here
    return back()->with('success', 'Subscribed successfully!');
})->name('newsletter.subscribe');

// Authentication routes (if you're using Laravel's built-in authentication)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/search', function () {
    // Add search logic here
    return view('search');
})->name('search');
Route::get('/cart', function () {
    // Add search logic here
    return view('cart');
})->name('cart');
// routes/web.php
Route::post('/login-popup', [App\Http\Controllers\Auth\LoginController::class, 'loginPopup'])->name('login.popup');


