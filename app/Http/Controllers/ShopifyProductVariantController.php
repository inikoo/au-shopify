<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 02:18:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use Arr;
use Illuminate\Http\Request;

class ShopifyProductVariantController extends Controller {

    function fetch(Request $request) {

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
            return $request->user()->shopifyProductVariants()->paginate(20);
        } else {
            return $request->user()->shopifyProductVariants()->whereIn('link_status', array_keys($openElements))->paginate(20);
        }

    }

}
