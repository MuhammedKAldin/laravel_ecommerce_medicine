<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
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

// Public Routes (Home, Products, Single Product, Cart, Checkout) ------------------------------------------------------
Route::get('/', function () {
    return view('home');
})->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products'); 
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

// Cart 
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/order-confirmation', [CartController::class, 'orderConfirmation'])->name('order-confirmation');

// Place / Confirm Order 
Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');
Route::get('/order-confirmation/{invoice}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// (Requires Auth) Routes ---------------------------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [UserController::class, 'index'])->name('dashboard');

    // Profile 
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update.address');
    Route::patch('/profile/contact', [ProfileController::class, 'updateContact'])->name('profile.update.contact');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Orders 
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
});

// (Requires Auth & Admin) Routes ----------------------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Product Routes
    Route::get('/products/logs', [App\Http\Controllers\Admin\ProductLogsController::class, 'index'])->name('products.logs');
    Route::get('/products/logs/{id}', [App\Http\Controllers\Admin\ProductLogsController::class, 'show'])->name('products.logs.show');
    Route::resource('/products', AdminProductController::class)->names('products');

    // Invoice Routes
    Route::get('/invoices', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('invoices.index'); 
    Route::get('/invoices/{invoice}', [App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoices.show');
    Route::put('/invoices/{invoice}', [App\Http\Controllers\Admin\InvoiceController::class, 'update'])->name('invoices.update');
    Route::post('/invoices/{invoice}/items', [App\Http\Controllers\Admin\InvoiceController::class, 'addItem'])->name('invoices.add-item');
    Route::delete('/invoices/{invoice}/items/{item}', [App\Http\Controllers\Admin\InvoiceController::class, 'deleteItem'])->name('invoices.delete-item');
});

require __DIR__.'/auth.php';
