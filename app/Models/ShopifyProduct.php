<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 18:34:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use App\Helpers\SyncOps\Shopify\ShopifyProductOps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property integer          $id
 * @property \App\Models\User $user
 * @mixin \Eloquent
 */
class ShopifyProduct extends Model {
    use ShopifyProductOps;

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function variants(): HasMany {
        return $this->hasMany('App\Models\ShopifyProductVariant');
    }



}
