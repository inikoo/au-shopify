<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 17:20:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Models\Customer;
use App\Models\PortfolioItem;
use Arr;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PortfolioItemController extends Controller {

    function delete($foreign_id): JsonResponse {

        $portfolioItem = PortfolioItem::firstWhere('foreign_id', $foreign_id);
        if ($portfolioItem) {
            try {
                $portfolioItem->delete();
                $portfolioItem->customer->updateNumberPortfolioProducts();

                return response()->json(
                    [
                        'success' => true,
                        'msg'     => 'portfolio_item '.$portfolioItem->id.' deleted'
                    ]
                );
            } catch (Exception $e) {
                return response()->json(
                    [
                        'success' => false,
                        'msg'     => 'portfolio_item can not be deleted'
                    ]
                );
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'msg'     => 'portfolio_item not found'
                ]
            );
        }
    }

    function create($customer_foreign_id, $portfolio_item_foreign_id): JsonResponse {

        $customer = Customer::firstWhere('foreign_id', $customer_foreign_id);
        $customer->store->storeEngine->setDatabase();
        $portfolioItem = $customer->synchronizePortfolioItem($portfolio_item_foreign_id);
        $customer->updateNumberPortfolioProducts();
        foreach ($customer->users as $user) {
            $user->updatePortfolioStats();
        }

        return response()->json(
            [
                'success' => true,
                'msg'     => 'portfolio_item '.$portfolioItem->id.' synchronized'
            ]
        );

    }

    function update(Request $request,$portfolio_item_foreign_id): JsonResponse {

        $request->validate(
            [
                'product_code' => 'sometimes',
            ]
        );

        $portfolioItem = PortfolioItem::firstWhere('foreign_id', $portfolio_item_foreign_id);



        if($request->exists('product_code')){

            $data=$portfolioItem->data;

           if($request->get('product_code')!=''){
               data_set($data, 'product_code', $request->get('product_code'));

           }else{
               Arr::forget($data, 'product_code');
           }

            $portfolioItem->data=$data;
            $portfolioItem->save();

        }

        return response()->json(
            [
                'success' => true,
                'msg'     => 'portfolio_item '.$portfolioItem->id.' updated'
            ]
        );


    }




}
