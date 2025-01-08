<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home\{
    HomeController, UserController, UserAddressController, ProductController, ContactController, CartController, OrderController, PaymentController, ReviewController, CheckoutController
};
use App\Http\Controllers\Auth\{LogInController, RegisterController, GoogleController};
use App\Http\Controllers\Admin\{
    AdminController, BannerController, CategoryController, ContactController as AdminContactController,
    OrderController as AdminOrderController, ProductController as AdminProductController, PromotionController,
    ReviewController as AdminReviewController, RoleController, UserController as AdminUserController,
    ProductImportController, ProductPromotionController
};
use App\Http\Middleware\IsAdmin;

// Google login
Route::get('redirect/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('callback/google', [GoogleController::class, 'callback'])->name('google.callback');

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Product routes
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('{id}', [ProductController::class, 'show'])->name('show');
    Route::post('/load-more', [ProductController::class, 'loadMoreProducts'])->name('loadMore');
    Route::post('/filter', [ProductController::class, 'filter'])->name('filter');
    Route::get('{productId}/check-stock', [ProductController::class, 'checkStock'])->name('check-stock');
});

// Cart routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'view'])->name('view');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
});

// Authentication routes
Route::get('/login', [LogInController::class, 'login'])->name('login');
Route::post('/login', [LogInController::class, 'authenticate']);
Route::post('/logout', [LogInController::class, 'logout'])->name('logout');
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'postRegister']);
Route::get('/password/reset', [UserController::class, 'showForgotForm'])->name('password.request');
Route::post('/password/email', [UserController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('password/update', [UserController::class, 'resetPassword'])->name('password.update');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [UserController::class, 'showProfile'])->name('show');
        Route::get('/avatar', [UserController::class, 'showAvatarPage'])->name('avatar');
        Route::post('/update-avatar', [UserController::class, 'updateAvatar'])->name('update.avatar');
        Route::post('/update-info', [UserController::class, 'updateProfile'])->name('update.info');
        Route::post('/add-address', [UserController::class, 'addAddress'])->name('add.address');
        Route::post('/change-password', [UserController::class, 'changePassword'])->name('change.password');
    });

    // Addresses
    Route::prefix('addresses')->name('profile.addresses.')->group(function () {
        Route::get('/', [UserAddressController::class, 'index'])->name('index');
        Route::post('/', [UserAddressController::class, 'store'])->name('store');
        Route::get('{id}', [UserAddressController::class, 'getAddress']);
        Route::put('{id}', [UserAddressController::class, 'update'])->name('update');
        Route::delete('{id}', [UserAddressController::class, 'destroy'])->name('destroy');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('{orderId}', [OrderController::class, 'show'])->name('show');
        Route::post('{orderId}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::get('{orderId}/success', [OrderController::class, 'success'])->name('success');
        Route::get('{orderId}/review', [ReviewController::class, 'create'])->name('review.create');
        Route::post('{orderId}/review', [ReviewController::class, 'store'])->name('review.store');
    });
});

// Admin routes
Route::prefix('admin')->middleware(['auth', IsAdmin::class])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Resources: banners, categories, products, promotions, etc.
    Route::resource('banners', BannerController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('promotions', PromotionController::class)->except(['show']);
    Route::resource('reviews', AdminReviewController::class)->only(['index']);
    Route::resource('users', AdminUserController::class)->except(['create', 'store']);
});





 //  Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('admin.dashboard');
 Route::get('/export', [App\Http\Controllers\Admin\DashboardController::class, 'exportStats'])->name('admin.dashboard.export');
 // Admin Dashboard
 Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('admin.dashboard');
 Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');

 // Admin Banners
 
 // Admin Categories

 



 //Admin Contacts





 Route::prefix('statistics')->name('admin.statistics.')->group(function () {
     Route::get('/dashboard', [StatisticsController::class, 'index'])->name('dashboard');
     Route::get('/revenue', [StatisticsController::class, 'revenueStats'])->name('revenue');
     Route::get('/products', [StatisticsController::class, 'productStats'])->name('products');
     Route::get('/customers', [StatisticsController::class, 'customerStats'])->name('customers');
     Route::post('/export', [StatisticsController::class, 'exportReport'])->name('export');
 });

 Route::get('/statistics', [StatisticsController::class, 'index'])->name('admin.statistics');
 Route::get('/statistics/inventory-history/{productId}', [StatisticsController::class, 'inventoryHistory']);
 Route::get('/statistics/export/excel', [StatisticsController::class, 'exportExcel'])->name('admin.statistics.export.excel');
 Route::get('/statistics/export/pdf', [StatisticsController::class, 'exportPdf'])->name('admin.statistics.export.pdf');


 // Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
 // Route::get('reports/print', [App\Http\Controllers\Admin\ReportController::class, 'printReport'])->name('admin.reports.print');
 // Route::get('admin/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('admin.reports.export');
 // Route::get('admin/reports/orders', [App\Http\Controllers\Admin\ReportController::class, 'showOrders'])->name('admin.reports.orders');

 Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
 Route::get('reports/export/excel', [App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])->name('admin.reports.export.excel');
 Route::get('reports/export/pdf', [App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])->name('admin.reports.export.pdf');

 Route::get('reports/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])->name('admin.reports.inventory');
 Route::get('reports/inventory/export/pdf', [App\Http\Controllers\Admin\ReportController::class, 'exportInventoryPdf'])->name('admin.reports.inventory.export.pdf');






 //Admin Oders
 

 // Admin Products
 


 Route::get('/admin/products/import', [\App\Http\Controllers\Admin\ProductImportController::class, 'showImportForm'])->name('admin.products.import.form');
 Route::post('/admin/products/import', [\App\Http\Controllers\Admin\ProductImportController::class, 'import'])->name('admin.products.import');

 Route::get('categories/{category}/products', [\App\Http\Controllers\Admin\PromotionController::class, 'getCategoryProducts'])
     ->name('admin.categories.products');

 // Admin Promotion


 Route::get('promotions/{id}/products', [\App\Http\Controllers\Admin\PromotionController::class, 'showProducts'])
     ->name('admin.promotions.products');


 // Admin Ratings
 


 // Admin Roles
 Route::get('roles', [App\Http\Controllers\Admin\RoleController::class, 'index'])->name('admin.roles.index');

 Route::get('product-promotions', [ProductPromotionController::class, 'index'])
     ->name('product-promotions.index');
 Route::post('product-promotions', [ProductPromotionController::class, 'update'])
     ->name('product-promotions.update');
 Route::delete('product-promotions', [ProductPromotionController::class, 'remove'])
     ->name('product-promotions.remove');








