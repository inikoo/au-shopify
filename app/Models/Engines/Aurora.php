<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:27:43 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Engines;

use App\Traits\Aurora\CustomerOps;


use App\Models\StoreEngine;
use App\Traits\Aurora\ImageOps;
use App\Traits\Aurora\ProductOps;
use App\Traits\Aurora\StoreOps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Class Aurora
 *
 * @property integer $id
 * @property string  $slug
 * @property array   $data
 * @property array $owners
 * @method static firstOrCreate(string[] $array)
 */
class Aurora extends Model {
    use StoreOps, ProductOps, CustomerOps, ImageOps;

    protected $table = 'aurora_engines';

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public $showBar = false;

    protected $guarded = [];

    public $owners = [];

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


    function fillAuroraData($fields, $legacy_data, $modifier = false): array {

        $data = [];
        foreach ($fields as $key => $legacy_key) {
            if (!empty($legacy_data->{$legacy_key})) {
                if ($modifier == 'strtolower') {
                    $value = strtolower($legacy_data->{$legacy_key});
                } elseif ($modifier == 'snake') {
                    $value = Str::snake($legacy_data->{$legacy_key});
                } elseif ($modifier == 'jsonDecode') {
                    $value = json_decode($legacy_data->{$legacy_key}, true);
                } else {
                    $value = $legacy_data->{$legacy_key};
                }
                Arr::set($data, $key, $value);
            }
        }
        return $data;

    }

}
