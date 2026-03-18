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
use App\Http\Controllers\Api\RegisterController;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
// Route::post('/send-otp', [RegisterController::class, 'sendOtp']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/auth/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');
Route::get('/test', function () {
    return response()->json(['message' => 'api laravel marche'], 200);
});

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

Route::middleware('auth:sanctum')->post('/cart/add', [CartController::class, 'addToCart']);
Route::middleware('auth:sanctum')->get('/cart', [CartController::class, 'cart']);
Route::middleware('auth:sanctum')->delete('/cart/{id}', [CartController::class, 'cartDelete']);
Route::middleware('auth:sanctum')->post('/place-order', [OrderController::class, 'placeOrder']);
Route::middleware('auth:sanctum')->get('/user/profile', [UserController::class, 'getUserProfile']);
 
Route::middleware('auth:sanctum')->group(function () {

    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    Route::post('/products/add', [ProductController::class, 'addProduct']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    //products similar
    });

    Route::get('/popular/products', [ProductController::class, 'getPopularProduct']);
    Route::get('/new/products', [ProductController::class, 'getNewProduct']);
    Route::get('/promotions', [PromotionController::class, 'index']);
    Route::get('/order/history/{id}', [ProductController::class, 'getHistory']);
    Route::get('/popular/categories', [ProductController::class, 'getPopularCategory']);

    Route::get('/categories', [CategoryController::class, 'categories']);
    Route::get('/subcategories', [CategoryController::class, 'showSubCategory']);
    Route::get('/products/{productId}', [ProductController::class, 'show']);
    Route::get('/products', [ProductController::class, 'index']);

Route::get('/vendor/{vendorId}/products', [ProductController::class, 'getProducts']);
