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
                'data' => 'required|json',
            ]
        );

        $store  = $request->user();
        $result = $store->registerCustomer($request);

        if ($result->success) {

            $result->customer->synchronizePortfolio();


            return response()->json(
                [
                    'success'     => true,
                    'customer_id' => $result->customer->id,
                    'store_id'    => $result->customer->store->slug,
                    'accessCode'  => $result->accessCode
                ]
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                ]
            );
        }
    }


    function verifyCustomer(Request $request): JsonResponse {

        $request->validate(
            [
                'accessCode' => 'required',
            ]
        );

        /**
         * @var $user \App\Models\User
         */
        $user=$request->user();

        return response()->json(


                $user->verifyCustomer($request)

        );
    }

}
