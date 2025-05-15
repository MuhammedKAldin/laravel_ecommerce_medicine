<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update.address');
    Route::patch('/profile/contact', [ProfileController::class, 'updateContact'])->name('profile.update.contact');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Main Routes
Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products'); 

Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

// Cart Routes
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/order-confirmation', [CartController::class, 'orderConfirmation'])->name('order-confirmation');
});

Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');
Route::get('/order-confirmation/{invoice}', [OrderController::class, 'confirmation'])->name('order.confirmation');

require __DIR__.'/auth.php';
