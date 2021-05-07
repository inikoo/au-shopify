<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 07 May 2021 18:07:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ProductController extends Controller {

    function fetch(Request $request) {


        $request->validate(
            [
                'page'     => 'sometimes|required|integer',
                'limit'     => 'sometimes|required|integer|max:1000',
                'code'     => 'sometimes|required|string',

            ]
        );

        return $request->user()->products()->paginate($request->get('limit'));


    }

}
