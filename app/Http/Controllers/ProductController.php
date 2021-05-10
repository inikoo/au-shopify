<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 07 May 2021 18:07:12 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProductController extends Controller {

    function fetchProducts(Request $request): LengthAwarePaginator {


        $request->validate(
            [
                'page'  => 'sometimes|required|integer',
                'limit' => 'sometimes|required|integer|max:1000',
            ]
        );


        return QueryBuilder::for(Product::where('store_id', $request->user()->id))->allowedFilters(
            [
                'code',
                'name'
            ]
        )->allowedSorts('id', 'code', 'unit_price', 'units', 'available', 'created_at', 'updated_at')->paginate($request->get('limit'));

    }

    /*
    function fetchProduct(Request $request) {

        QueryBuilder::for(User::where('id', 42)) // base query instead of model
        ->allowedIncludes(['posts'])
            ->where('activated', true) // chain on any of Laravel's query methods
            ->first();

    }
    */

}
