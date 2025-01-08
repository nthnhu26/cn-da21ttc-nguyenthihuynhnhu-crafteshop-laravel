<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LogInController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\Auth\GoogleController;

use App\Http\Controllers\Home\ProductController;
use App\Http\Controllers\Home\ContactController;
use App\Http\Controllers\Home\CartController;
use App\Http\Controllers\Home\ReviewController;
use App\Http\Controllers\Home\HomeController;
use App\Http\Controllers\Home\UserController;
use App\Http\Controllers\Home\OrderController;
use App\Http\Controllers\Home\PaymentController;
use App\Http\Controllers\Home\CheckoutController;


// Trang chủ
Route::get('/', [HomeController::class, 'index'])->name('home');

// Sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
Route::post('/products/sort', [ProductController::class, 'sort'])->name('products.sort');
Route::post('/products/load-more', [ProductController::class, 'loadMore'])->name('products.loadMore');

// Giới thiệu
Route::get('/about', [HomeController::class, 'about'])->name('about');

// Trang liên hệ
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [App\Http\Controllers\Home\ContactController::class, 'submit'])->name('contact.submit');

Route::post('/mark-notification-as-read/{notification}', function ($id) {
    auth()->user()->notifications->where('id', $id)->first()->markAsRead();
    return redirect()->back();
})->name('markNotificationAsRead');

// Tìm kiếm
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/search-results', [ProductController::class, 'searchResults'])->name('products.searchResults');
//Route::post('/products/filter', [ProductController::class, 'filter'])->name('products.filter');

// Đăng nhập và đăng ký
Route::get('/login', [LogInController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::get('/register', [RegisterController::class, 'register'])->name('register');
Route::post('/register', [RegisterController::class, 'postRegister']);

// Quên mật khẩu
Route::get('/password/reset', [UserController::class, 'showForgotForm'])->name('password.request');
Route::post('/password/email', [UserController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [UserController::class, 'showResetForm'])->name('password.reset');
Route::post('password/update', [UserController::class, 'resetPassword'])->name('password.update');

// Đăng nhập bằng Google
Route::get('redirect/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('callback/google', [GoogleController::class, 'callback'])->name('google.callback');



// Giỏ hàng
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

//Giỏ hàng thanh nav
Route::get('/cart/get-items', [CartController::class, 'getItems'])->name('cart.getItems');
Route::get('/products/{productId}/check-stock', [ProductController::class, 'checkStock'])->name('products.check-stock');


Route::middleware(['auth'])->group(function () {

    // Đăng xuất
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



    // Đánh giá sản phẩm
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::put('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/reviews/{productId}/create', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Thông tin tài khoản 
    Route::get('/profile', [UserController::class, 'index'])->name('profile.show');
    Route::put('/profile/update-avatar', [UserController::class, 'updateAvatar'])->name('profile.update.avatar');
    Route::post('/profile/update-info', [UserController::class, 'updateInfo'])->name('profile.update.info');
    Route::put('/profile/change-password', [UserController::class, 'changePassword'])->name('profile.change.password');
    Route::post('/profile/address', [UserController::class, 'store'])->name('profile.add.address');
    Route::get('/profile/addres', [UserController::class, 'index'])->name('profile.addresses');
    //   Route::put('/profile/update-address/{id}', [UserController::class, 'update'])->name('profile.update.address');
    Route::put('/profile/address/{id}', [UserController::class, 'update'])->name('profile.update.address');
    Route::put('/profile/set-password', [UserController::class, 'setPassword'])->name('profile.set.password');

    Route::delete('/profile/address/{id}', [UserController::class, 'destroy'])->name('profile.delete.address');
    Route::get('/profile/districts/{provinceCode}', [UserController::class, 'getDistricts'])->name('profile.get.districts');
    Route::get('/profile/wards/{districtCode}', [UserController::class, 'getWards'])->name('profile.get.wards');

    // Route::get('/profile/addres', [UserController::class, 'index'])->name('profile.addresses'); // Trang quản lý địa chỉ
    // Route::post('/profile/address', [UserController::class, 'store'])->name('profile.add.address'); // Thêm địa chỉ
    // Route::put('/profile/address/{id}', [UserController::class, 'update'])->name('profile.update.address'); // Cập nhật địa chỉ
    // Route::delete('/profile/address/{id}', [UserController::class, 'destroy'])->name('profile.delete.address'); // Xóa địa chỉ


    Route::delete('/profile/delete-account', [UserController::class, 'deleteAccount'])->name('profile.deleteAccount');

    Route::get('/orders/{orderId}/reviews/{productId}', [OrderController::class, 'viewReview'])->name('reviews.view');
    Route::put('/reviews/{review}', [OrderController::class, 'editReview'])->name('reviews.edit');
    Route::delete('/reviews/{review}', [OrderController::class, 'deleteReview'])->name('reviews.delete');


    // Thanh toán

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{orderId}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::prefix('checkout')->name('checkout.')->group(function () {

        Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('placeOrder');
        Route::post('/check-coupon', [CheckoutController::class, 'checkCoupon'])->name('checkCoupon');
        Route::get('/success/{orderId}', [CheckoutController::class, 'success'])->name('success'); // Route yêu cầu tham số {orderId}
    });
    Route::post('/checkout/change-address', [CheckoutController::class, 'changeAddress'])->name('checkout.changeAddress');

    //     Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
    //     Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('orders.place');
    //     Route::get('/calculate-shipping/{addressId}', [CheckoutController::class, 'calculateShipping'])->name('calculate.shipping');
    //     Route::get('/check-coupon/{code}', [CheckoutController::class, 'checkCoupon'])->name('check.coupon');
    //     Route::post('/apply-coupon', [CheckoutController::class, 'applyPromotion'])->name('checkout.apply-promotion');
    //     Route::get('/orders/{orderId}/success', [CheckoutController::class, 'success'])->name('orders.success');
    //     Route::post('coupons/check', [CheckoutController::class, 'checkCoupon'])->name('coupons.check');
    //     Route::post('/buy-now', [CheckoutController::class, 'buyNow'])->name('buy.now');

    //     Route::post('/checkout/update-shipping-address', [CheckoutController::class, 'updateShippingAddress'])->name('checkout.update-shipping-address');
    //     Route::get('/check-coupon/{code}', [CheckoutController::class, 'checkCoupon'])->name('check.coupon');
    //     //  Route::get('/payment/momo/{orderId}', [PaymentController::class, 'payment'])->name('payment.momo');
    //  //   Route::post('/update-checkout-address', [CheckoutController::class, 'updateAddress'])->name('checkout.updateAddress');
    //     Route::post('/update-checkout-address', [CheckoutController::class, 'updateAddress']);
    //     Route::post('/check-coupon', [CheckoutController::class, 'checkCoupon']);
    //     Route::post('/payment/momo', [PaymentController::class, 'payment'])->name('payment.momo');
    //     Route::post('/payment/momo/ipn', [PaymentController::class, 'ipn'])->name('payment.momo.ipn');
    //     // Thanh toán
    //     //   Route::post('/payment', [PaymentController::class, 'payment'])->name('payment');
    //     //   Route::get('/payment/momo/{orderId}', [PaymentController::class, 'momoPayment'])->name('payment.momo');
    //     // In your web.php routes file
    //     Route::get('/payment/momo/callback', [PaymentController::class, 'momoCallback'])->name('payment.momo.callback');

    Route::get('/orders/{order}/review', [OrderController::class, 'showReviewForm'])->name('orders.review.form');
    Route::post('/orders/{order}/review', [OrderController::class, 'submitReview'])->name('orders.review.submit');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', IsAdmin::class]], function () {

    // Đăng xuất
    Route::post('/logout', [App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('admin.logout');

    // Danh mục
    Route::get('categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('categories/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::post('/categories/export', [App\Http\Controllers\Admin\CategoryController::class, 'export'])->name('admin.categories.export');
    Route::post('/categories/delete-selected', [App\Http\Controllers\Admin\CategoryController::class, 'deleteSelected'])->name('admin.categories.deleteSelected');


    // Sản phẩm
    Route::get('products', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.products.index');
    Route::get('products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.products.create');
    Route::post('products', [App\Http\Controllers\Admin\ProductController::class, 'store'])->name('admin.products.store');
    Route::get('products/{product}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('products/{product}', [App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('products/{product}/manage-images', [App\Http\Controllers\Admin\ProductController::class, 'manageImages'])->name('admin.products.manage-images');
    Route::post('products/{product}/store-images', [App\Http\Controllers\Admin\ProductController::class, 'storeImages'])->name('admin.products.store-images');
    Route::delete('products/{product}/delete-image/{image}', [App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('admin.products.delete-image');
    Route::post('admin/products/{product_id}/set-main-image/{image_id}', [App\Http\Controllers\Admin\ProductController::class, 'setMainImage'])->name('admin.products.set-main-image');

    Route::get('products/{product}/inventory', [App\Http\Controllers\Admin\ProductController::class, 'manageInventory'])->name('admin.products.manage-inventory');
    Route::post('products/{product}/images', [App\Http\Controllers\Admin\ProductController::class, 'addImages'])->name('admin.products.add-images');
    Route::post('products/{product}/update-inventory', [\App\Http\Controllers\Admin\ProductController::class, 'updateInventory'])->name('admin.products.update-inventory');
    Route::post('products/{product}/update-inventory', [\App\Http\Controllers\Admin\ProductController::class, 'updateInventory'])->name('admin.products.update-inventory');
    Route::get('products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'show'])->name('admin.products.show');
    Route::get('products/import/form', [App\Http\Controllers\Admin\ProductController::class, 'showImportForm'])->name('admin.products.import-form');
    Route::post('/products/import', [App\Http\Controllers\Admin\ProductController::class, 'import'])->name('admin.products.import');

    Route::post('/products/export', [App\Http\Controllers\Admin\ProductController::class, 'export'])->name('admin.products.export');
    Route::post('/products/delete-selected', [App\Http\Controllers\Admin\ProductController::class, 'deleteSelected'])->name('admin.products.deleteSelected');



    // Đơn hàng
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::put('orders/{orderId}', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');


    // Khách hàng
    Route::get('users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::post('users/{user}/update-status', [App\Http\Controllers\Admin\UserController::class, 'updateStatus'])->name('admin.users.update-status');

    // Đánh giá
    Route::get('reviews', [App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('admin.reviews.index');
    Route::put('reviews/{review}/hide', [App\Http\Controllers\Admin\ReviewController::class, 'hide'])->name('admin.reviews.hide');
    Route::put('reviews/{review}/show', [App\Http\Controllers\Admin\ReviewController::class, 'show'])->name('admin.reviews.show');

    // Liên hệ
    Route::get('contacts', [App\Http\Controllers\Admin\ContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('contacts/{contact}', [App\Http\Controllers\Admin\ContactController::class, 'show'])->name('admin.contacts.show');
    Route::post('contacts/{contact}/respond', [App\Http\Controllers\Admin\ContactController::class, 'respond'])->name('admin.contacts.respond');

    // Khuyến mãi
    Route::get('promotions', [App\Http\Controllers\Admin\PromotionController::class, 'index'])->name('admin.promotions.index');
    Route::get('promotions/create', [App\Http\Controllers\Admin\PromotionController::class, 'create'])->name('admin.promotions.create');
    Route::post('promotions', [App\Http\Controllers\Admin\PromotionController::class, 'store'])->name('admin.promotions.store');
    Route::get('promotions/{promotion}/edit', [App\Http\Controllers\Admin\PromotionController::class, 'edit'])->name('admin.promotions.edit');
    Route::put('promotions/{promotion}', [App\Http\Controllers\Admin\PromotionController::class, 'update'])->name('admin.promotions.update');
    Route::delete('promotions/{promotion}', [App\Http\Controllers\Admin\PromotionController::class, 'destroy'])->name('admin.promotions.destroy');
    Route::get('categories/{category}/products', [\App\Http\Controllers\Admin\PromotionController::class, 'getCategoryProducts'])
        ->name('admin.categories.products');

    // Banner
    Route::get('banners', [App\Http\Controllers\Admin\BannerController::class, 'index'])->name('admin.banners.index');
    Route::get('banners/create', [App\Http\Controllers\Admin\BannerController::class, 'create'])->name('admin.banners.create');
    Route::post('banners', [App\Http\Controllers\Admin\BannerController::class, 'store'])->name('admin.banners.store');
    Route::get('banners/{banner}/edit', [App\Http\Controllers\Admin\BannerController::class, 'edit'])->name('admin.banners.edit');
    Route::put('banners/{banner}', [App\Http\Controllers\Admin\BannerController::class, 'update'])->name('admin.banners.update');
    Route::delete('banners/{banner}', [App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('admin.banners.destroy');

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.dashboard.index');










    // Báo cáo
    Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');

    // Revenue Reports
    Route::get('/reports/revenue', [App\Http\Controllers\Admin\ReportController::class, 'revenue'])
        ->name('admin.reports.revenue');

    // Inventory Reports  
    Route::get('/reports/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])
        ->name('admin.reports.inventory');

    // Export Routes
    Route::get('/reports/revenue/export/{type}', [App\Http\Controllers\Admin\ReportController::class, 'exportRevenue'])
        ->name('admin.reports.revenue.export')
        ->where('type', 'excel|pdf');

    Route::get('/reports/inventory/export/{type}', [App\Http\Controllers\Admin\ReportController::class, 'exportInventory'])
        ->name('admin.reports.inventory.export')
        ->where('type', 'excel|pdf');

    Route::get('/bestsellers', [App\Http\Controllers\Admin\ReportController::class, 'bestSellers'])->name('admin.reports.bestsellers');
    Route::get('/customers', [App\Http\Controllers\Admin\ReportController::class, 'customers'])->name('admin.reports.customers');
    Route::get('/category', [App\Http\Controllers\Admin\ReportController::class, 'categoryReport'])->name('admin.reports.category');
});


Route::get('/admin/statistics/export-excel', [App\Http\Controllers\Admin\StatisticsController::class, 'exportExcel'])->name('statistics.export.excel');
Route::get('/admin/statistics/export-pdf', [App\Http\Controllers\Admin\StatisticsController::class, 'exportPdf'])->name('statistics.export.pdf');

// Route::get('/admin/statistics', [App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('admin.statistics.index');
Route::get(
    '/admin/statistics/inventory-details/{productId}',
    [App\Http\Controllers\Admin\StatisticsController::class, 'getInventoryDetails']
)
    ->name('admin.statistics.inventory.details');

Route::get('/inventory', [App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
Route::get('/inventory/{id}', [App\Http\Controllers\Admin\InventoryController::class, 'show'])->name('inventory.show');
Route::post('/inventory/{id}/add-stock', [App\Http\Controllers\Admin\InventoryController::class, 'addStock'])->name('inventory.add-stock');


Route::get('/admin/statistics/order-status', [App\Http\Controllers\Admin\StatisticsController::class, 'getOrderStatusChartData'])->name('admin.statistics.order-status');
Route::get('/admin/statistics/chart-data', [App\Http\Controllers\Admin\StatisticsController::class, 'getChartData'])->name('admin.statistics.chart.data');

Route::get('/export', [App\Http\Controllers\Admin\ExportController::class, 'export'])->name('export');

Route::fallback(function () {
    return view('404');
});
