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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/shop/{name}', [ProductController::class, 'productPerCategory'])->name('productPerCategory');
Route::get('/shop/detail/{id}', [ProductController::class, 'productDetail'])->name('shop.detail');
Route::get('/search', [HomeController::class, 'search'])->name('search');

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');

Route::middleware('auth')->get('/notifications', [NotificationController::class, 'index']);
// Checkout
Route::get('/cart/checkout', [OrderController::class, 'showCheckout'])->name('orders.show.checkout');
Route::post('/cart/checkout', [OrderController::class, 'placeOrder'])->name('checkout.process');
// Admin routes group
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Vendors
    Route::get('/vendors/{vendorId}', [AdminController::class, 'changeVendorStatus'])->name('changeVendorStatus');
    
    // Products - Use resource for full CRUD, but override index name if needed
    Route::resource('products', ProductController::class);
    // If you want 'admin.products' specifically for index (instead of 'admin.products.index'), add this explicit route BEFORE the resource
    Route::get('products', [ProductController::class, 'index'])->name('products'); // This overrides the resource index name
    
    // Users
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('showUser');
    Route::get('categories', [AdminController::class, 'categories'])->name('categories');
    // Categories - Use resource for consistency
    Route::resource('categories', AdminController::class)->only([ 'store', 'update', 'destroy']);
    
    // Orders
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [AdminController::class, 'viewOrder'])->name('viewOrder');
    Route::get('/orders/validate', [AdminController::class, 'validateOrder'])->name('order.validate');
});

// Ads
Route::get('/ads/create', [AdController::class, 'create'])->middleware('auth');

// Auth routes
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('loginForm');
Route::post('/login', [CustomLoginController::class, 'login'])->name('login');

Route::post('/send-otp', [OTPController::class, 'generateOTP']);
Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);

// Registration with OTP
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'step1'])->name('register.step1');
Route::get('/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
Route::post('/verify-otp', [RegisterController::class, 'step2'])->name('register.step2');
Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend.otp');

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
    Route::post('/remove/cart/', [ProductController::class, 'removeCart'])->name('cart.remove');
    // routes/web.php
Route::put('/cart/update/{cart}', [ProductController::class, 'update'])->name('cart.update');
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

require __DIR__ . '/auth.php';