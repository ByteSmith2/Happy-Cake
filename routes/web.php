<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = collect();

    if (Schema::hasTable('products')) {
        $products = Product::with('category')->latest()->get();
    }

    return view('home', [
        'products' => $products,
    ]);
})->name('home');

Route::get('/gio-hang', [CartController::class, 'index'])->name('cart.index');
Route::post('/gio-hang/them/{product}', [CartController::class, 'add'])->name('cart.add');
Route::put('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
Route::get('/gio-hang/xoa/{key}', [CartController::class, 'remove'])
    ->where('key', '[A-Za-z0-9_-]+')
    ->name('cart.remove');

Route::get('/thanh-toan', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/thanh-toan', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/thanh-toan/hoan-tat/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
