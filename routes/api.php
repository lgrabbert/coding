<?php

declare(strict_types=1);

use App\Http\Controllers\OrdersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::prefix('v1')->group(function () {
    Route::apiResource('orders', OrdersController::class)->only([
        'index'
    ]);
    Route::apiResource('order/{id}', OrderController::class)->only([
        'index', 'store', 'show', 'destroy'
    ]);
});


