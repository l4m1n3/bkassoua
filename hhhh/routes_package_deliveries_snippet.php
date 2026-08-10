<?php

// À ajouter dans routes/api.php, dans le groupe protégé par le middleware 'auth:sanctum'

use App\Http\Controllers\PackageDeliveryController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/delivery-zones', [PackageDeliveryController::class, 'zones']);
    Route::post('/package-deliveries/estimate', [PackageDeliveryController::class, 'estimate']);
    Route::get('/package-deliveries', [PackageDeliveryController::class, 'index']);
    Route::post('/package-deliveries', [PackageDeliveryController::class, 'store']);
    Route::get('/package-deliveries/{packageDelivery}', [PackageDeliveryController::class, 'show']);
    Route::patch('/package-deliveries/{packageDelivery}/status', [PackageDeliveryController::class, 'updateStatus']);
    Route::patch('/package-deliveries/{packageDelivery}/cancel', [PackageDeliveryController::class, 'cancel']);
});
