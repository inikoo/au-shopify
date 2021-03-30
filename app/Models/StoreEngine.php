<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 23 Mar 2021 16:01:31 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use stdClass;


/**
 * Class StoreEngine
 *
 * @property integer                    $id
 * @property array                      $data
 * @property \App\Models\Engines\Aurora engine
 * @mixin \Eloquent
 */
class StoreEngine extends Model {

    protected $table = 'store_engines';

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function engine(): MorphTo {
        return $this->morphTo(__FUNCTION__, 'store_engine_type', 'store_engine_id');
    }

    public function stores(): HasMany {
        return $this->hasMany('App\Models\Store');
    }

    public function synchronizeProducts($store, $bar) {
        $this->engine->owners  = [
            'storeEngine' => $this,
            'store'       => $store
        ];
        $this->engine->showBar = $bar;

        $this->engine->synchronizeProducts();
    }

    public function synchronizeStore($foreignStoreId) {
        return $this->engine->synchronizeStore($this, $foreignStoreId);
    }

    public function synchronizePortfolio($customer) {
        $this->engine->synchronizePortfolio($customer);
    }

    public function registerCustomer($store, $customerData): stdClass {
        $this->setDatabase();

        return $this->engine->registerCustomer($store, $customerData);
    }

    public function setDatabase() {
        $this->engine->setDatabase(Arr::get($this->data, 'database'));
    }

    public function saveStoreEngineToken($token, $storeId) {
        $this->setDatabase();
        $this->engine->saveStoreEngineToken($token, $storeId);
    }


}
