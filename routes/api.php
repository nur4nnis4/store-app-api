<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::apiResource('products', ProductController::class);

Route::get('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/{id}', 'show');
    Route::get('/products-popular', 'showPopular');

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/products', 'store');
        Route::patch('/products/{id}', 'update')->middleware('product.seller');
        Route::delete('/products/{id}', 'destroy')->middleware('product.seller');
    });
});
