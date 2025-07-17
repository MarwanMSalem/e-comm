<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\Web\OrderController as WebOrderController;
use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\Web\AuthController as WebAuthController;

// Home route (already present)
Route::get('/', function () {
    return view('welcome');
});

// Show register form
Route::get('web/register', function () {
    return view('auth.register');
})->name('web.register');

// Show login form
Route::get('web/login', function () {
    return view('auth.login');
})->name('web.login');

// Authentication routes
Route::post('web/register', [WebAuthController::class, 'register']);
Route::post('web/login', [WebAuthController::class, 'login']);
Route::post('web/logout', [WebAuthController::class, 'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('web')->group(function () {
    // Products
    Route::get('products', [WebProductController::class, 'index']);
    Route::get('products/{id}', [WebProductController::class, 'show']);
    Route::post('products', [WebProductController::class, 'store']);
    Route::put('products/{product}', [WebProductController::class, 'update']);
    Route::delete('products/{product}', [WebProductController::class, 'destroy']);

    // Orders
    Route::get('orders', [WebOrderController::class, 'index']);
    Route::get('orders/my', [WebOrderController::class, 'myOrders']);
    Route::get('orders/{id}', [WebOrderController::class, 'show']);
    Route::post('orders', [WebOrderController::class, 'store']);
    Route::put('orders/{order}', [WebOrderController::class, 'update']);
    Route::delete('orders/{order}', [WebOrderController::class, 'destroy']);

    // Users
    Route::get('users', [WebUserController::class, 'index']);
    Route::get('users/{id}', [WebUserController::class, 'show']);
    Route::put('users/{user}', [WebUserController::class, 'update']);
});
