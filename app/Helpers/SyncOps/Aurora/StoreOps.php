<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:42:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Helpers\SyncOps\Aurora;

use Illuminate\Support\Facades\DB;

trait StoreOps {

    public function synchronizeStore($storeEngine, $foreignStoreId) {


        $store = false;

        $sql = ' * from `Store Dimension` where `Store Key`=?';
        foreach (DB::connection('aurora')->select("select $sql", [$foreignStoreId]) as $foreignStore) {

            /**
             * @var $store \App\Models\Store
             */
            $store = $storeEngine->stores()->updateOrCreate(
                [
                    'foreign_id' => $foreignStore->{'Store Key'},
                ], [
                    'name'     => $foreignStore->{'Store Name'},
                    'url'      => $foreignStore->{'Store URL'},
                    'locale'   => $foreignStore->{'Store Locale'},
                    'currency' => $foreignStore->{'Store Currency Code'},
                ]
            );


            $sql = "`Store Dimension` set `Store Shopify Key`=? where `Store Key`=?";
            DB::connection('aurora')->statement(
                "update $sql", [
                                 $store->id,
                                 $foreignStore->{'Store Key'}
                             ]
            );


        }

        return $store;

    }

    public function saveStoreEngineToken($token, $storeID) {

        $sql = "`Store Dimension` set `Store Shopify API Key`=? where `Store Key`=?";
        DB::connection('aurora')->statement(
            "update $sql", [
                             $token,
                             $storeID
                         ]
        );

    }

}

