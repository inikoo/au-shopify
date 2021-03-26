<?php

use App\Http\Controllers\AppRegisterController;
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
    if (Auth::user()->customer_id) {
        return view('dashboard');
    } else {
        return view('verification');
    }
}
)->middleware(['auth.shopify'])->name('home');

Route::post('/verify', [AppRegisterController::class, 'verifyCustomer'])->middleware(['auth.shopify']);



