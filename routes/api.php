<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 15:48:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PortfolioItemController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(
    [
        'auth:api',
        'sanctum.abilities:is-shopify_product_app'
    ]
)->group(
    function () {
        Route::any(
            '/products', [
                           ProductController::class,
                           'fetch'
                       ]
        );
    }
);

Route::middleware(
    [
        'auth:api',
        'sanctum.abilities:is-aurora'
    ]
)->group(
    function () {

        Route::any(
            '/register', [
            CustomerController::class,
            'registerCustomer'
        ]
        );
        Route::post(
            '/portfolio_item/{foreign_id}', [
            PortfolioItemController::class,
            'update'
        ]
        );
        Route::delete(
            '/portfolio_item/{foreign_id}', [
            PortfolioItemController::class,
            'delete'
        ]
        );
        Route::post(
            '/customer/{customer_foreign_id}/portfolio_item/{portfolio_item_foreign_id}', [
            PortfolioItemController::class,
            'create'
        ]
        );


    }
);

Route::get(
    '/user', function (Request $request) {
    return $request->user();
}
)->middleware(['auth:api']);;






