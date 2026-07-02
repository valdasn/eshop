<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/* Public Routes */
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/shop-all', [ProductController::class, 'index'])->name('products.index');


Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('products.show');

Route::get('/category/{slug}', [ProductController::class, 'category'])->name('categories.show');

/*Cart (Guests can add items)*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product:slug}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');

Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove'); 
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/*Authenticated Routes*/
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return redirect('/');
    })->name('dashboard');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // STRIPE CHECKOUT
    Route::get('/checkout', [CartController::class, 'showCheckout'])->name('checkout');
    
    // Starts the Stripe session
    Route::post('/checkout/stripe', [CartController::class, 'stripeCheckout'])->name('checkout.stripe');
    
    // Handles the return from Stripe
    Route::get('/checkout/success', [CartController::class, 'stripeSuccess'])->name('checkout.success');

    // Order History
    Route::get('/my-orders', [CartController::class, 'orderHistory'])->name('orders.history');
});

require __DIR__.'/auth.php';