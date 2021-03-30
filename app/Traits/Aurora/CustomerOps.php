<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:42:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Traits\Aurora;

use App\Models\Portfolio;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use stdClass;

trait CustomerOps {


    public function synchronizeCustomer($store, $customerForeignID): stdClass {

        $result = (object)[
            'success' => false
        ];

        $sql = ' * from `Customer Dimension` where `Customer Store Key`=? and `Customer Key`=?';


        foreach (
            DB::connection('aurora')->select(
                "select $sql", [
                                 $store->foreign_id,
                                 $customerForeignID
                             ]
            ) as $foreignCustomer
        ) {


            $customer = $store->customers()->firstorCreate(
                [
                    'foreign_id' => $foreignCustomer->{'Customer Key'}
                ], [
                    'name' => $foreignCustomer->{'Customer Name'}
                ]
            );

            $result->success  = true;
            $result->customer = $customer;


            $sql = "`Customer Dimension` set `Customer Shopify Key`=? where `Customer Key`=?";
            DB::connection('aurora')->statement(
                "update $sql", [
                                 $customer->id,
                                 $foreignCustomer->{'Customer Key'}
                             ]
            );


        }

        return $result;


    }


    public function registerCustomer($store, $customerForeignData): stdClass {

        $result = $this->synchronizeCustomer($store, $customerForeignData->get('id'));

        if ($result->success) {
            $accessCode         = $result->customer->accessCodes()->create([]);
            $result->accessCode = $accessCode->access_code;


            return $result;
        }

        return $result;


    }

    function synchronizePortfolio($customer) {


        $sql = "* from `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=?";
        foreach (DB::connection('aurora')->select("select $sql", [$customer->foreign_id]) as $auroraData) {
            $product = (new Product)->firstWhere('foreign_id', $auroraData->{'Customer Portfolio Product ID'});

            if ($product->id) {

                $data = [];
                if ($auroraData->{'Customer Portfolio Reference'} != '') {
                    $data = [
                        'product_code' => $auroraData->{'Customer Portfolio Reference'}
                    ];
                }


                Portfolio::withTrashed()->updateOrCreate(
                    [
                        'foreign_id' => $auroraData->{'Customer Portfolio Key'},

                    ], [
                        'customer_id' => $customer->id,
                        'product_id'  => $product->id,
                        'data'        => $data,
                        'created_at'  => $auroraData->{'Customer Portfolio Creation Date'},
                        'deleted_at'  => (($auroraData->{'Customer Portfolio Customers State'} == 'Removed' and $auroraData->{'Customer Portfolio Removed Date'} != '') ? $auroraData->{'Customer Portfolio Removed Date'} : null),
                    ]
                );
            }
        }
    }


}

