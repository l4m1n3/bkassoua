<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AdController;
use App\Http\Controllers\RegisterController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);

Route::get('/ads', [AdController::class, 'index']); // Liste des annonces actives
Route::get('/ads/{id}', [AdController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
});
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/cart/add', [CartController::class, 'addToCart']);
Route::middleware('auth:sanctum')->get('/cart', [CartController::class, 'cart']);
Route::middleware('auth:sanctum')->post('/place-order', [OrderController::class, 'placeOrder']);
Route::middleware('auth:sanctum')->get('/user/profile', [UserController::class, 'getUserProfile']);
// Route::middleware('auth:sanctum')->get('product/{vendorId}', [ProductController::class, 'getProducts']);
Route::get('/vendor/{vendorId}/products', [ProductController::class, 'getProducts']);
Route::get('/popular/products', [ProductController::class, 'getPopularProduct']);
Route::get('/new/products', [ProductController::class, 'getNewProduct']);
Route::get('/promotions', [PromotionController::class, 'index']);
Route::get('/order/history/{id}', [ProductController::class, 'getHistory']);

// Route::post('/send-otp', [OTPController::class, 'generateOTP']);
// Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);
// Étape 1: Enregistrement des infos + envoi OTP
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'step1'])->name('register');
// Route::get('/verify-otp', [RegisterController::class, 'showVerifyOtpForm'])->name('verify.otp.form');
// Route::post('/verify-otp', [RegisterController::class, 'step2'])->name('register.step2');
// Route::post('/resend-otp', [RegisterController::class, 'resendOtp'])->name('resend.otp');
// Route::get('/', function () { return view('welcome'); })->name('home');

Route::post('/products/add', [ProductController::class, 'addProduct']);
// web.php ou api.php
Route::delete('/products/{id}', [ProductController::class, 'destroy']);


Route::get('categories', [CategoryController::class, 'categories']);
Route::get('products/{productId}', [ProductController::class, 'show']);
Route::get('products', [ProductController::class, 'index']);
Route::post('login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/test', function () {
    return response(['message' => 'api laravel marche'], 200);
});
