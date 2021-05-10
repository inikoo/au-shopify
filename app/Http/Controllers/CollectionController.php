<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Mon, 10 May 2021 18:56:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\Collection;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class CollectionController extends Controller {

    function fetchCollections(Request $request) {


        $request->validate(
            [
                'page'  => 'sometimes|required|integer',
                'limit' => 'sometimes|required|integer|max:1000',
                'code'  => 'sometimes|required|string',

            ]
        );

        if ($request->get('code')) {


            $response = QueryBuilder::for(Collection::where('store_id', $request->user()->id))->allowedIncludes(['products'])->where('code', $request->get('code'))->first();

            if (!$response) {
                return response()->json(['message' => 'Not Found.'], 404);
            } else {
                return $response;
            }

        }

        return QueryBuilder::for(Collection::where('store_id', $request->user()->id))->allowedFilters(
            [
                'code',
                'name'
            ]
        )->allowedSorts('id', 'code', 'products_number', 'created_at', 'updated_at')->allowedIncludes(['products'])->paginate($request->get('limit'));

    }


}
