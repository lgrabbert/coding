<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::prefix('v1')->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/order', [OrderController::class, 'store']);
    Route::get('/order/{id}', [OrderController::class, 'show']);
    Route::delete('/order/{id}', [OrderController::class, 'destroy']);
});
