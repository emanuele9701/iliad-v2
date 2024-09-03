<?php

use Illuminate\Support\Facades\Route;


// Home page
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Ordini
Route::prefix('orders')->group(function () {
    Route::get('/', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/create', [\App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/', [\App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    Route::get('/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
    Route::get('/{order}/edit', [\App\Http\Controllers\OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/{order}', [\App\Http\Controllers\OrderController::class, 'update'])->name('orders.update');
    Route::delete('/{order}', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('orders.destroy');
});

// Prodotti
Route::prefix('products')->group(function () {
    Route::get('/', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');
    Route::get('/create', [\App\Http\Controllers\ProductController::class, 'create'])->name('products.create');
    Route::post('/', [\App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/{product}', [\App\Http\Controllers\ProductController::class, 'show'])->name('products.show');
    Route::get('/{product}/edit', [\App\Http\Controllers\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/{product}', [\App\Http\Controllers\ProductController::class, 'update'])->name('products.update');
    Route::delete('/{product}', [\App\Http\Controllers\ProductController::class, 'destroy'])->name('products.destroy');
});
