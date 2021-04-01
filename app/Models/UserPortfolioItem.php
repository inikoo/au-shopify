<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 14:39:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer              $id
 * @property \App\Models\Customer $customer
 * @property \App\Models\Product  $product
 */
class UserPortfolioItem extends Model {
    use softDeletes;


    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];


    public function user(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function portfolioItem(): BelongsTo {
        return $this->belongsTo('App\Models\PortfolioItem');
    }


}
