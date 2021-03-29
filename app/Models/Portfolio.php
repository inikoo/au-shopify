<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Mon, 29 Mar 2021 16:19:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer $id
 * @property \App\Models\Customer   $customer
 * @property \App\Models\Product   $product
 */
class Portfolio extends Model {
    use softDeletes;


    protected $table='customer_product';

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];


    public function customer(): BelongsTo {
        return $this->belongsTo('App\Models\Customer');
    }

    public function product(): BelongsTo {
        return $this->belongsTo('App\Models\Product');
    }


}
