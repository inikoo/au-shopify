<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 23 Mar 2021 15:54:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Engines;

use App\Models\Product;
use App\Models\StoreEngine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Class AuroraStoreEngine
 *
 * @property integer $id
 * @property string  $slug*
 * @property array   $data
 * @method static firstOrCreate(string[] $array)
 */
class AuroraStoreEngine extends Model {

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];


    public function setDatabase($database) {

        $database_settings = data_get(config('database.connections'), 'aurora');
        data_set($database_settings, 'database', $database);
        config(['database.connections.aurora' => $database_settings]);

    }

    public function storeEngines(): MorphToMany {
        return $this->morphedByMany(StoreEngine::class, 'store_engine_type', 'store_engine_id');
    }


    /** @noinspection PhpUnused */
    public function synchronizeProducts($storeEngine, $store) {


        $this->setDatabase(Arr::get($storeEngine->data, 'database'));

        $sql = ' * from `Product Dimension` where `Product Store Key`=? limit 10';
        foreach (DB::connection('aurora')->select("select $sql", [$store->foreign_id]) as $foreign_product) {


            $available = $foreign_product->{'Product Availability'};
            if (!$available or $available < 0) {
                $available = 0;
            }
            $status = true;
            if ($foreign_product->{'Product Status'} == 'Discontinued') {
                $status = false;
            }

            $units = $foreign_product->{'Product Units Per Case'};
            if ($units == 0) {
                $units = 1;
            }

            if ($foreign_product->{'Product Valid From'} == '0000-00-00 00:00:00') {
                $created_at = null;
            } else {
                $created_at = $foreign_product->{'Product Valid From'};
            }


            switch ($foreign_product->{'Product Status'}) {
                case 'InProcess':
                    $state = 'creating';
                    break;
                case 'Discontinuing':
                    $state = 'discontinuing';
                    break;
                case 'Discontinued':
                    $state = 'discontinued';
                    break;
                default:
                    $state = 'active';
            }


            /**
             * @var $product Product
             */
            $product = $store->products()->updateOrCreate(
                [
                    'foreign_id' => $foreign_product->{'Product ID'},
                ], [
                    'state'     => $state,
                    'status'    => $status,
                    'name'      => $foreign_product->{'Product Name'},
                    'code'      => $foreign_product->{'Product Code'},
                    'units'     => $units,
                    'available' => $available,

                    'unit_price' => $foreign_product->{'Product Price'} / $units,
                    'created_at' => $created_at

                ]
            );

            $sql = "`Product Dimension` set `Product Shopify Key`=? where `Product ID`=?";
            DB::connection('aurora')->statement(
                "update $sql", [
                                 $product->id,
                                 $foreign_product->{'Product ID'}
                             ]
            );


        }

    }


    public function synchronizeStore($storeEngine, $foreignStoreId) {

        $this->setDatabase(Arr::get($storeEngine->data, 'database'));

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
                    'name' => $foreignStore->{'Store Name'},
                    'url'  => $foreignStore->{'Store URL'},
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

    public function synchronizeStoreEngine($foreignData) {

        $storeEngine = false;

        $sql = ' * from `Account Dimension` where `Account Key`=?';
        foreach (DB::connection('aurora')->select("select $sql", [$foreignData['id']]) as $foreignStoreEngine) {


            $storeEngine = StoreEngine::firstOrCreate(
                [
                    'slug' => 'au-'.$foreignData['code'],

                ], [
                    'foreign_id' => $foreignStoreEngine->{'Account Key'},
                    'name'       => $foreignStoreEngine->{'Account Name'},
                    'url'        => $foreignStoreEngine->{'Account System Public URL'},
                    'data'       => [
                        'code'     => $foreignStoreEngine->{'Account Code'},
                        'database' => $foreignData['database'],
                    ]
                ]
            );


            $sql = "`Account Data` set `Account Shopify Key`=? where `Account Key`=?";
            DB::connection('aurora')->statement(
                "update $sql", [
                                 $storeEngine->id,
                                 $foreignStoreEngine->{'Account Key'}
                             ]
            );


        }

        return $storeEngine;

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

    public function synchronizeCustomer($store, $customerForeignID): stdClass {

        $result= (object) [
            'success'=>false
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

            $result->success=true;
            $result->customer=$customer;



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

        $result   = $this->synchronizeCustomer($store, $customerForeignData->get('id'));

        if($result->success){
            $accessCode= $result->customer->accessCodes()->create(
                [

                ]
            );
            $result->accessCode=$accessCode->access_code;
            return $result;
        }
        return $result;


    }

}
