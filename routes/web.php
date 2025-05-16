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

// Dashboard Route
Route::get('/dashboard', [UserController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
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
Route::get('/cart', [CartController::class, 'cart'])->name('cart');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
Route::get('/order-confirmation', [CartController::class, 'orderConfirmation'])->name('order-confirmation');

// Orders (requires auth)
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
});

// Place Order Route
Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place.order');
Route::get('/order-confirmation/{invoice}', [OrderController::class, 'confirmation'])->name('order.confirmation');

// Admin Routes
Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
Route::resource('/admin/products', AdminProductController::class)->names('admin.products');

// Admin Invoice Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/invoices', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [App\Http\Controllers\Admin\InvoiceController::class, 'show'])->name('invoices.show');
    Route::put('/invoices/{invoice}', [App\Http\Controllers\Admin\InvoiceController::class, 'update'])->name('invoices.update');
    Route::post('/invoices/{invoice}/items', [App\Http\Controllers\Admin\InvoiceController::class, 'addItem'])->name('invoices.add-item');
    Route::delete('/invoices/{invoice}/items/{item}', [App\Http\Controllers\Admin\InvoiceController::class, 'deleteItem'])->name('invoices.delete-item');
});

require __DIR__.'/auth.php';
