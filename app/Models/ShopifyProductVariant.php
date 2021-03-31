<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 23:40:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



/**
 * @property integer $id
 * @mixin \Eloquent
 */
class ShopifyProductVariant extends Model {

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];

    public function shopify_product(): BelongsTo {
        return $this->belongsTo(ShopifyProduct::class);
    }

    public function portfolio(): BelongsTo {
        return $this->belongsTo(Portfolio::class);
    }


}
