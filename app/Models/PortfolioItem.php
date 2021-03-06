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
 * @property integer              $id
 * @property integer              $customer_id
 * @property integer              $product_id
 * @property array                $data
 * @property \App\Models\Customer $customer
 * @property \App\Models\Product  $product
 * @mixin \Eloquent
 */
class PortfolioItem extends Model {
    use softDeletes;


    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];

    protected static function booted() {
        static::created(
            function ($portfolio) {


            }
        );
    }

    function saveUserPortfolio() {
        foreach ($this->customer->users as $user) {
            $user->portfolioItems()->updateOrCreate(
                [
                    'portfolio_item_id' => $this->id,
                ], [
                    'product_id' => $this->product_id,
                    'code'       => $this->product->code,
                    'name'       => $this->product->name

                ]
            );

        }
    }


    public function customer(): BelongsTo {
        return $this->belongsTo('App\Models\Customer');
    }

    public function product(): BelongsTo {
        return $this->belongsTo('App\Models\Product');
    }


}
