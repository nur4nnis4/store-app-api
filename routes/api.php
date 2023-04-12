<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::apiResource('products', ProductController::class);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::controller(UserController::class)->group(function () {
    Route::get('/users', 'index');
    Route::get('/users/{id}', 'show');

    Route::middleware(['auth:sanctum', 'account.owner'])->group(function () {
        Route::get('/users-account/{id}', 'getUserAccount');
        Route::patch('/users/{id}', 'update');
        Route::delete('/users/{id}', 'destroy');
    });
});
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
