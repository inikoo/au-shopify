<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 19:53:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\AccessCode;
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
