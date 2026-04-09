<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');
Route::get('/orders/latest', [AdminController::class, 'latest'])->name('orders.latest');
Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/shop/{name}', [ProductController::class, 'productPerCategory'])->name('productPerCategory');
Route::get('/shop/detail/{id}', [ProductController::class, 'productDetail'])->name('shop.detail');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/contact/privacy', [ContactController::class, 'privacy'])->name('contact.privacy');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

Route::middleware('auth')->get('/notifications', [NotificationController::class, 'index']);

//promotions et ads routes
Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions.index');
Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
Route::get('/promotions/{id}', [PromotionController::class, 'show'])->name('promotions.show');
Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('promotions.update');
Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('promotions.destroy');


// Checkout
Route::get('/cart/checkout/show', [OrderController::class, 'showCheckout'])->name('orders.show.checkout');
Route::post('/cart/checkout/order', [OrderController::class, 'placeOrder'])->name('checkout.process');

// Admin routes group
Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/ads', [AdminController::class, 'ads'])->name('ads.index');
    Route::get('/ads/create', [AdminController::class, 'createAd'])->name('ads.create');
    Route::post('/ads', [AdminController::class, 'storeAd'])->name('ads.store');
    Route::get('/ads/{id}', [AdminController::class, 'showAd'])->name('ads.show');
    Route::put('/ads/{id}', [AdminController::class, 'updateAd'])->name('ads.update');
    Route::delete('/ads/{id}', [AdminController::class, 'destroyAd'])->name('ads.destroy');
    // Vendors
    Route::get('/vendors/{vendorId}', [AdminController::class, 'changeVendorStatus'])->name('changeVendorStatus');

    // Products - Use resource for full CRUD, but override index name if needed
    Route::resource('products', ProductController::class);

    Route::get('products', [ProductController::class, 'index'])->name('products'); // This overrides the resource index name

    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('showUser');
    Route::get('categories', [AdminController::class, 'categories'])->name('categories');
    Route::get('categories/sub', [AdminController::class, 'showSubCategory'])
        ->name('categories.showSubCategory');

    Route::get('categories/sub/attributes', [AdminController::class, 'showAttributes'])
        ->name('Subcategories.showAttributes');
    Route::post('categories/sub/attributes',        [AdminController::class, 'storeAttribute'])->name('attributes.store');
    Route::put('categories/sub/attributes/{id}',    [AdminController::class, 'updateAttribute'])->name('attributes.update');
    // Categories - Use resource for consistency
    // Route::resource('categories', AdminController::class)->only(['storeCategory', 'updateCategory', 'destroy']);
    Route::post('categories', [AdminController::class, 'storeCategory'])
        ->name('categories.storeCategory');
    Route::post('categories/sub', [AdminController::class, 'storeSubCategory'])
        ->name('categories.storeSubCategory');
    Route::put('categories/{id}', [AdminController::class, 'updateCategory'])
        ->name('categories.updateCategory');

    Route::delete('categories/{id}', [AdminController::class, 'destroyCategories'])
        ->name('categories.destroy');
    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'viewOrder'])->name('viewOrder');
    Route::get('/orders/validate', [AdminController::class, 'validateOrder'])->name('order.validate');
    Route::post('/orders/{order}/cancel', [AdminController::class, 'cancel'])
        ->name('admin.orders.cancel');

    Route::post('/orders/{order}/validate-payment', [AdminController::class, 'validatePayment'])
        ->name('admin.orders.validatePayment');

    Route::post('/orders/{order}/status', [AdminController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');
});

// Ads
Route::get('/ads/create', [AdController::class, 'create'])->middleware('auth');

// Auth routes
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('loginForm');
Route::post('/login', [CustomLoginController::class, 'login'])->name('login');
// Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend.otp');
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/send-otp', [RegisterController::class, 'sendOtp']);
Route::post('/send-otp', [OTPController::class, 'generateOTP']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOTP'])->name('verify.otp');
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::get('/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.store');

// Vendor routes
Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
Route::get('/vendor/register', [VendorController::class, 'showForm'])->name('vendor.register');
Route::post('/vendor/register', [VendorController::class, 'store'])->name('vendor.store');


// Vendor products
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::post('/products/user', [VendorController::class, 'storeProduct'])->name('user.store');
    Route::put('/products/{id}', [VendorController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [VendorController::class, 'destroyProduct'])->name('products.delete');
    // Add index/create if needed: Route::get('/products', ...)->name('products.index');
});

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Orders and Payments
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/payments', [PaymentController::class, 'processPayment'])->name('payments.process');

// Cart
Route::middleware(['auth'])->group(function () {
    Route::post('/add/cart/', [ProductController::class, 'storeCart'])->name('cart.add');
    Route::get('/show/cart/', [ProductController::class, 'showCart'])->name('cart');
    Route::delete('/remove/cart/', [ProductController::class, 'removeCart'])->name('cart.remove');
    // routes/web.php
    Route::put('/cart/update/{cart}', [ProductController::class, 'updateCart'])->name('cart.update');
});



// Orders management (prefixed)
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::post('/{order}/validate-payment', [OrderController::class, 'validatePayment'])->name('validate');
    Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    Route::patch('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::get('/export', [OrderController::class, 'export'])->name('export');
});

Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');
require __DIR__ . '/auth.php';
