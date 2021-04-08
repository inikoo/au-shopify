<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 08 Apr 2021 15:25:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopifyProductController extends Controller {

    function create(Request $request): JsonResponse {

        $result=[
            'success'=>true,
            'formatted_status'=>'caca'
        ];

        return response()->json($result);

    }


}
