<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 08 Apr 2021 15:25:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\UserPortfolioItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopifyProductController extends Controller {

    function create(Request $request): JsonResponse {

        $request->validate(
            [
                'user_portfolio_item_id' => 'exists:user_portfolio_items,id',
            ]
        );
        $user_portfolio_items = UserPortfolioItem::find($request->get('user_portfolio_item_id'));
        $result               = $user_portfolio_items->createShopifyProduct();

        return response()->json($result);

    }

}
