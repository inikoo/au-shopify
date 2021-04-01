<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Mon, 22 Mar 2021 18:34:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\AccessCode;
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

            $result->customer->synchronizePortfolioItems();


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
        $user = $request->user();


        $result = (object)[
            'success' => false,
            'reason'  => 'server-error'
        ];

        $accessCode = AccessCode::withTrashed()->firstWhere('access_code', $request->get('accessCode'));


        if ($accessCode) {
            if ($accessCode->trashed()) {
                $result->reason = 'expired-access-code';
            } else {

                $user->customer_id = $accessCode->customer_id;

                $user->state = 'linked';

                $user->save();

                $user->customer->accessCodes()->forceDelete();
                $user->customer->updateNumberUsers();

                $result->success = true;
                $result->reason  = 'verified';
            }
        } else {
            $result->reason = 'invalid-access-code';
        }


        return response()->json($result);
    }

}
