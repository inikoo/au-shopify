<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Mon, 22 Mar 2021 18:34:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppRegisterController extends Controller {

    function registerCustomer(Request $request): JsonResponse {


        $request->validate(
            [
                'id'   => 'required',
                'name' => 'required',
            ]
        );

        $customer = $request->user()->customers()->firstorCreate(
            [
                'foreign_id' => $request->get('id')
            ], [
                'name' => $request->get('name')
            ]
        );

        $accessCode = $customer->accessCodes()->create(
            [

            ]
        );


        return response()->json(
            [
                'customer_id' => $customer->id,
                'store_id'    => $customer->store->slug,
                'accessCode'  => $accessCode->access_code
            ]
        );


    }

}
