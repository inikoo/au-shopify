<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 17:29:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

use App\Http\Controllers\ShopifyProductController;
use App\Http\Controllers\ShopifyProductVariantController;
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
    return (Auth::user()->customer_id ? view('app') : view('verification'));
}
)->middleware(['auth.shopify'])->name('products');

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

Route::get(
    '/shopify_products_variants', [
                           ShopifyProductVariantController::class,
                           'fetch'
                       ]
)->middleware(['auth.shopify']);
Route::get(
    '/shopify_products/create', [
                           ShopifyProductController::class,
                           'create'
                       ]
)->middleware(['auth.shopify']);



Route::get(
    '/linked_products', [
                           ShopifyProductVariantController::class,
                           'fetchLinked'
                       ]
)->middleware(['auth.shopify']);

Route::get(
    '/portfolio_items', [
                          UserController::class,
                           'fetchProducts'
                       ]
)->middleware(['auth.shopify']);

