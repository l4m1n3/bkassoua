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
use App\Http\Controllers\Api\PackageDeliveryController;






Route::middleware('auth:sanctum')->post(
    '/save-fcm-token',
    [UserController::class, 'saveFcmToken']
);
/*
|--------------------------------------------------------------------------
| AUTH & OTP (Public)
|--------------------------------------------------------------------------
*/
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/verify-otp', [RegisterController::class, 'verifyOtp']);
Route::post('/resend-otp', [RegisterController::class, 'resendOtp']);
Route::post('/forgot-password', [RegisterController::class, 'forgotPassword']);
Route::post('/reset-password',[RegisterController::class, 'resetPassword']);
Route::post('/send-otp', [RegisterController::class, 'sendOtp']);
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);
Route::post('/auth/apple/callback', [AuthController::class, 'appleCallback']);
/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Accessible sans authentification)
|--------------------------------------------------------------------------
*/

// 🔹 Annonces
Route::get('/ads', [AdController::class, 'index']);
Route::get('/ads/{id}', [AdController::class, 'show']);

// 🔹 Catégories
Route::get('/categories', [CategoryController::class, 'categories']);
Route::get('/popular/categories', [ProductController::class, 'getPopularCategory']);

// 🔹 Produits
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{productId}', [ProductController::class, 'show']);
Route::get('/vendor/{vendorId}/products', [ProductController::class, 'getProducts']);
Route::get('/popular/products', [ProductController::class, 'getPopularProduct']);
Route::get('/new/products', [ProductController::class, 'getNewProduct']);
Route::get('/products/similar/{id}', [ProductController::class, 'similarProducts']);
// 🔹 Promotions
Route::get('/promotions', [PromotionController::class, 'index']);
// routes/api.php — ajouter
Route::get('/attributes', [ProductController::class, 'getAttributs']);
// Route::get('/order/history/{id}', [ProductController::class, 'getHistory']);
/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES (Require auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/user/profile', [UserController::class, 'getUserProfile']);
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::delete('/profile', [UserController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'cart']);
    Route::delete('/cart/{id}', [CartController::class, 'cartDelete']);

    /*
    |--------------------------------------------------------------------------
    | ORDERS
    |--------------------------------------------------------------------------
    */
    Route::post('/place-order', [OrderController::class, 'placeOrder']);
    Route::get('/order/history/{id}', [ProductController::class, 'getHistory']);
    Route::get('/regions', [OrderController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | PRODUCT MANAGEMENT (Vendeur/Admin)
    |--------------------------------------------------------------------------
    */
    // Route::post('/products/add', [ProductController::class, 'addProduct']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| TEST ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/test', fn() =>
    response(['message' => 'api laravel marche'], 200)
);
  /*
    |--------------------------------------------------------------------------
    | DELIVERY MANAGEMENT (LIVRAISON)
    |--------------------------------------------------------------------------
    */
// Route::middleware('auth:sanctum')->group(function () {
    Route::get('/delivery-zones', [PackageDeliveryController::class, 'zones']);
    Route::post('/package-deliveries/estimate', [PackageDeliveryController::class, 'estimate']);
    Route::get('/package-deliveries', [PackageDeliveryController::class, 'index']);
    Route::post('/package-deliveries', [PackageDeliveryController::class, 'store']);
    Route::get('/package-deliveries/{packageDelivery}', [PackageDeliveryController::class, 'show']);
    Route::patch('/package-deliveries/{packageDelivery}/status', [PackageDeliveryController::class, 'updateStatus']);
    Route::patch('/package-deliveries/{packageDelivery}/cancel', [PackageDeliveryController::class, 'cancel']);
// });
