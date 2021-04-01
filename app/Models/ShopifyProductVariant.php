<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 23:40:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


/**
 * @property integer $id
 * @property string  $link_status
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

    public function portfolio_item(): BelongsTo {
        return $this->belongsTo(PortfolioItem::class);
    }

    public function product(): HasOneThrough {
        return $this->HasOneThrough(PortfolioItem::class, Product::class);
    }

    public function calculateLinkStatus(){
        if($this->link_status=='unknown'){
           // $portfolio=
        }
    }

}
