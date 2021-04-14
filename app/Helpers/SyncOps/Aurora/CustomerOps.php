<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:42:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Helpers\SyncOps\Aurora;

use App\Models\PortfolioItem;
use App\Models\Product;
use Arr;
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




            $customer = $store->customers()->updateOrCreate(
                [
                    'foreign_id' => $foreignCustomer->{'Customer Key'}
                ], [
                    'name'    => $foreignCustomer->{'Customer Name'},
                    'balance' => (double) $foreignCustomer->{'Customer Account Balance'}
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

    function synchronizePortfolioItems($customer) {


        $sql = "* from `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=?";
        foreach (DB::connection('aurora')->select("select $sql", [$customer->foreign_id]) as $auroraData) {
            $this->savePortfolioItem($customer->id, $auroraData);


        }
    }

    function synchronizePortfolioItem($customer_id, $portfolio_item_foreign_id) {

        $sql = "* from `Customer Portfolio Fact` where `Customer Portfolio Key`=?";
        foreach (DB::connection('aurora')->select("select $sql", [$portfolio_item_foreign_id]) as $auroraData) {
            return $this->savePortfolioItem($customer_id, $auroraData);
        }

        return false;

    }

    private function savePortfolioItem($customer_id, $auroraData) {


        $product = (new Product)->firstWhere('foreign_id', $auroraData->{'Customer Portfolio Product ID'});

        if ($product) {

            $data = [];
            if ($auroraData->{'Customer Portfolio Reference'} != '') {
                $data = [
                    'product_code' => $auroraData->{'Customer Portfolio Reference'}
                ];
            }

            $portfolioItem = PortfolioItem::withTrashed()->updateOrCreate(
                [
                    'foreign_id' => $auroraData->{'Customer Portfolio Key'},

                ], [
                    'customer_id' => $customer_id,
                    'product_id'  => $product->id,
                    'data'        => $data,
                    'created_at'  => $auroraData->{'Customer Portfolio Creation Date'},
                    'deleted_at'  => (($auroraData->{'Customer Portfolio Customers State'} == 'Removed' and $auroraData->{'Customer Portfolio Removed Date'} != '') ? $auroraData->{'Customer Portfolio Removed Date'} : null),
                ]
            );
            $portfolioItem->saveUserPortfolio();

            $sql = "`Customer Portfolio Fact` set `Customer Portfolio Shopify Key`=? , `Customer Portfolio Shopify State`=? where `Customer Portfolio Key`=?";
            DB::connection('aurora')->statement(
                "update $sql", [
                                 $portfolioItem->id,
                                 (Arr::get($portfolioItem->data, 'link_state', 'unlinked') == 'linked' ? 'Linked' : 'Unlinked'),
                                 $auroraData->{'Customer Portfolio Key'}
                             ]
            );

            return $portfolioItem;

        }

        return false;


    }


}

