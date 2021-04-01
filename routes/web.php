<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get(
    '/', function () {
    return (Auth::user()->customer_id ? view('dashboard') : view('verification'));
}
)->middleware(['auth.shopify'])->name('dashboard');

Route::get(
    '/products', function () {
    return (Auth::user()->customer_id ? view('products') : view('verification'));
}
)->middleware(['auth.shopify'])->name('products');

Route::post(
    '/verify', [
                 UserController::class,
                 'verifyUser'
             ]
)->middleware(['auth.shopify']);



