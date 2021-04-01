<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 15:48:29 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PortfolioItemController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->any('/register', [CustomerController::class, 'registerCustomer']);

Route::middleware('auth:api')->post('/portfolio_item/{foreign_id}', [PortfolioItemController::class, 'update']);
Route::middleware('auth:api')->delete('/portfolio_item/{foreign_id}', [PortfolioItemController::class, 'delete']);
Route::middleware('auth:api')->post('/customer/{customer_foreign_id}/portfolio_item/{portfolio_item_foreign_id}', [PortfolioItemController::class, 'create']);







