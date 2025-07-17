<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductController as WebProductController;
use App\Http\Controllers\Web\OrderController as WebOrderController;
use App\Http\Controllers\Web\UserController as WebUserController;
use App\Http\Controllers\Web\AuthController as WebAuthController;

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Register and login forms (GET)
Route::get('web/register', function () {
    return view('auth.register');
})->name('web.register');

Route::get('web/login', function () {
    return view('auth.login');
})->name('web.login');

// Register and login actions (POST)
Route::post('web/register', [WebAuthController::class, 'register'])->name('web.register.submit');
Route::post('web/login', [WebAuthController::class, 'login'])->name('web.login.submit');
Route::post('web/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('web.logout');

// Public product views
Route::get('products', [WebProductController::class, 'index'])->name('products.index');
Route::get('products/{product}', [WebProductController::class, 'show'])->name('products.show');

// Admin-only product edit/update (must be authenticated and admin)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('products/{product}/edit', [WebProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [WebProductController::class, 'update'])->name('products.update');
});

// Home route
Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth:sanctum');

// (Optional) Other web routes for orders/users can be added here, following the same pattern.

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('orders', [WebOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}/edit', [WebOrderController::class, 'edit'])->name('orders.edit');
    Route::put('orders/{order}', [WebOrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [WebOrderController::class, 'destroy'])->name('orders.destroy');
    Route::post('products/{product}/order', [\App\Http\Controllers\Web\OrderController::class, 'storeFromProduct'])->name('orders.store.from_product');
    // ... other order routes as needed ...
});
