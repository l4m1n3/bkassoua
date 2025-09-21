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
// Route::get('/contact', [ContactController::class, 'index'])->name('contact');

Route::middleware('auth')->get('/notifications', [NotificationController::class, 'index']);
Route::middleware(['auth'])->get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
Route::middleware(['auth'])->get('/admin/vendors/{vendorId}', [AdminController::class, 'changeVendorStatus'])->name('admin.changeVendorStatus');
Route::middleware(['auth'])->resource('admin/products', ProductController::class);
Route::middleware(['auth'])->get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
Route::middleware(['auth'])->get('/admin/users/{id}', [AdminController::class, 'showUser'])->name('admin.showUser');
Route::middleware(['auth'])->get('admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
Route::middleware(['auth'])->get('admin/categories/{id}', [AdminController::class, 'destroyCategories'])->name('admin.categories.destroy');
Route::middleware(['auth'])->post('admin/categories/', [AdminController::class, 'storeCategory'])->name('admin.categories.store');
Route::middleware(['auth'])->put('admin/categories/{id}', [AdminController::class, 'updateCategory'])->name('admin.categories.update');
Route::middleware(['auth'])->get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
Route::middleware(['auth'])->get('/admin/orders/{id}', [AdminController::class, 'viewOrder'])->name('admin.viewOrder');
Route::middleware(['auth'])->get('/admin/orders/validate', [AdminController::class, 'validateOrder'])->name('admin.order.validate');
Route::get('/ads/create', [AdController::class, 'create'])->middleware('auth');


Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->name('loginForm');
Route::post('/login', [CustomLoginController::class, 'login'])->name('login');

Route::post('/send-otp', [OTPController::class, 'generateOTP']);
Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);
// Étape 1: Enregistrement des infos + envoi OTP
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'step1'])->name('register');
Route::get('/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
Route::post('/verify-otp', [RegisterController::class, 'step2'])->name('register.step2');
Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend.otp');
// Route::get('/', function () { return view('welcome'); })->name('home');

Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])->name('vendor.dashboard');
Route::get('/vendor/register', [VendorController::class, 'showForm'])->name('vendor.register');
Route::post('/vendor/register', [VendorController::class, 'store'])->name('vendor.store');
Route::post('/vendor/register/user', [VendorController::class, 'storeProduct'])->name('vendor.user.store');
Route::put('/vendor/update/products/{id}', [VendorController::class, 'updateProduct'])->name('vendor.products.update');
Route::delete('/vendor/update/delete/{id}', [VendorController::class, 'destroyProduct'])->name('vendor.products.delete');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
//Commande et paiement
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::post('/payments', [PaymentController::class, 'processPayment'])->name('payments.process');
Route::middleware(['auth'])->post('/add/cart/', [ProductController::class, 'storeCart'])->name('cart.add');
Route::middleware(['auth'])->get('/show/cart/', [ProductController::class, 'showCart'])->name('cart');
Route::get('/cart/checkout', [OrderController::class, 'showCheckout'])->name('orders.show.checkout');
// a revoir, passer a letape check avant de passer commande
Route::post('/cart/checkout', [OrderController::class, 'placeOrder'])->name('cart.checkout');
// Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::prefix('orders')->group(function() {
    Route::get('/', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::post('/{order}/validate-payment', [OrderController::class, 'validatePayment'])->name('admin.orders.validate');
    Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::patch('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
    Route::get('/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/export', [OrderController::class, 'export'])->name('admin.orders.export');
});
require __DIR__ . '/auth.php';
