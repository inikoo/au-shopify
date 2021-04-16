<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 19:53:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\AccessCode;
use Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller {



    function verifyUser(Request $request): JsonResponse {

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
                $user->save();

                $user->synchronizeStore();
                $user->synchronizeProducts();
                $user->synchronizePortfolio();
                $user->updateStats();
                $user->setupShopifyStore();


                $user->state = 'linked';
                $user->save();



                $user->customer->accessCodes()->forceDelete();
                $user->customer->updateNumberUsers();

                $result->success = true;
                $result->reason  = 'verified';
            }
        } else {
            $result->reason = 'invalid-access-code';
            $result->errorMessage=__('The access code is invalid 🤔');
        }


        return response()->json($result);
    }

    function fetchPortfolioItems(Request $request) {


        $request->validate(
            [
                'elements' => 'required|json',
                'page'     => 'required|integer',
            ]
        );

        $elements = json_decode($request->get('elements'), true);
        $openElements = Arr::where(
            $elements, function ($value) {
            return $value;
        }
        );


        if (count($elements) == count($openElements)) {
            return $request->user()->portfolioItems()->paginate(20);
        } else {
            return $request->user()->portfolioItems()->whereIn('status', array_keys($openElements))->paginate(20);
        }



    }

}
