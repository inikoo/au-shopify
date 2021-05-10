<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 07 May 2021 18:07:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller {

    function fetchProducts(Request $request) {


        $request->validate(
            [
                'page'  => 'sometimes|required|integer',
                'limit' => 'sometimes|required|integer|max:1000',
                'code'  => 'sometimes|required|string',

            ]
        );

        if($request->get('code')){


            $response= QueryBuilder::for(Product::where('store_id', $request->user()->id))
            ->allowedIncludes(['images'])
                ->where('code', $request->get('code'))
                ->first();

            if(!$response){
                return response()->json(['message' => 'Not Found.'], 404);
            }else{
                return $response;
            }

        }

        return QueryBuilder::for(Product::where('store_id', $request->user()->id))->allowedFilters(
            [
                'code',
                'name'
            ]
        )->allowedSorts('id', 'code', 'unit_price', 'units', 'available', 'created_at', 'updated_at')->allowedIncludes(['images'])->paginate($request->get('limit'));

    }

    /*
    function fetchProduct(Request $request) {

        QueryBuilder::for(User::where('id', 42)) // base query instead of model
        ->allowedIncludes(['posts'])
            ->where('activated', true) // chain on any of Laravel query methods
            ->first();

    }
    */

}
