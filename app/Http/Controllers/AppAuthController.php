<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Mon, 22 Mar 2021 18:34:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;



use Illuminate\Http\Request;

class AppAuthController extends Controller {

    function createAccessCode(Request $request) {





        $request->validate(
            [
                'customer_id'    => 'required',
            ]
        );




        $tenant= (new Tenant)->firstWhere('slug', $request->subdomain);


        $tenant->makeCurrent();

        $request->validate(
            [
                'user_id' => 'required|exists:users,id',
            ]
        );

        $user = (new User)->find($request->user_id);

        $accessCode = $user->createAccessCode();

        return response()->json(
            [
                'code' => $accessCode->code
            ]
        );


    }

}
