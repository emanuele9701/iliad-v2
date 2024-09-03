<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['as' => 'api.'],function () {
    Route::get('orders/stats', [\App\Http\Controllers\OrderApiController::class, 'stats'])->name('orders.stats');
    Route::get('products/search', [\App\Http\Controllers\ProductApiController::class, 'search'])->name('products.search');
    Route::resource('products',\App\Http\Controllers\ProductApiController::class);
    Route::resource('orders',\App\Http\Controllers\OrderApiController::class);
});
