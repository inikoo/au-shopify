<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:27:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Engines;

use App\Traits\Aurora\CustomerOps;


use App\Models\StoreEngine;
use App\Traits\Aurora\ProductOps;
use App\Traits\Aurora\StoreOps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;

/**
 * Class Aurora
 *
 * @property integer $id
 * @property string  $slug
 * @property array   $data
 * @method static firstOrCreate(string[] $array)
 */
class Aurora extends Model {
    use StoreOps, ProductOps, CustomerOps;

    protected $table = 'aurora_engines';

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
        DB::purge('aurora');
    }

    public function storeEngines(): MorphToMany {
        return $this->morphedByMany(StoreEngine::class, 'store_engine_type', 'store_engine_id');
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


}
