<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:42:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Traits\Aurora;

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
            $accessCode         = $result->customer->accessCodes()->create(
                [

                ]
            );
            $result->accessCode = $accessCode->access_code;

            return $result;
        }

        return $result;


    }


}

