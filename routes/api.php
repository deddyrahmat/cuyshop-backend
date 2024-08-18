<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckShippingCostController;
use App\Http\Controllers\API\NotifPaymentController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;
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

    // address user
    Route::resource("address", AddressController::class);

    // Routes untuk CheckShippingCostController
    Route::get('/provinces', [CheckShippingCostController::class, 'province']);
    Route::get('/cities', [CheckShippingCostController::class, 'city']);
    Route::post('/check-shipping-cost', [CheckShippingCostController::class, 'checkShippingCost']);


    // payment
    // Route::post('/create-pay', [PaymentController::class, 'createPay']);

    Route::resource("order", OrderController::class);
});
Route::post('/midtrans-notif', NotifPaymentController::class);

require __DIR__ . '/auth.php';
