<?php

use App\Livewire\Checkout;
use App\Livewire\ListProducts;
use App\Livewire\User\Cart;
use App\Livewire\User\Order;
use App\Livewire\User\Orders;
use App\Livewire\User\Profile;
use App\Livewire\ViewProduct;
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

Route::get('/', ListProducts::class);

Route::get('category/{category}', ListProducts::class);
Route::get('search', ListProducts::class);

Route::get('product/{product}', ViewProduct::class);

Route::get('checkout/{order}', Checkout::class);

Route::prefix('user')->group(function () {
    Route::get('/', Profile::class);
    Route::get('cart', Cart::class);
    Route::get('orders', Orders::class);
    Route::get('order/{order}', Order::class);
});
