<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckShippingCostController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::resource("products", ProductController::class);
Route::resource("categories", CategoryController::class);

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum'])->group(function () {
    // Route untuk mendapatkan informasi user
    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    // Routes untuk CheckShippingCostController
    Route::get('/provinces', [CheckShippingCostController::class, 'province']);
    Route::get('/cities', [CheckShippingCostController::class, 'city']);
    Route::post('/check-shipping-cost', [CheckShippingCostController::class, 'checkShippingCost']);


    // payment
    Route::post('/get-snap-token', [PaymentController::class, 'getSnapToken']);
});

require __DIR__ . '/auth.php';
