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


/**
 * Class StoreEngine
 *
 * @property integer $id
 * @property array   $data
 * @mixin \Eloquent
 */
class StoreEngine extends Model {

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


}
