<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 02:18:28 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class ShopifyProductVariantController extends Controller {

    function fetch(Request $request) {

        return $request->user()->shopify_product_variants()->paginate(20);

    }

    function fetchLinked(Request $request) {

        return $request->user()->shopify_product_variants()->paginate(20);

    }
}
