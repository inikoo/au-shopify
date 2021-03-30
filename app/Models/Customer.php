<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 23 Mar 2021 18:40:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @property integer number_users
 * @property integer number_portfolio_products
 * @property \App\Models\Store store
 * @mixin \Eloquent
 */
class Customer extends Model {
    use HasSlug;

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];


    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()->generateSlugsFrom(
            [
                'name',
                'store_id'
            ]
        )->saveSlugsTo('slug');
    }

    public function store(): BelongsTo {
        return $this->belongsTo(Store::class);
    }

    public function accessCodes(): HasMany {
        return $this->hasMany('App\Models\AccessCode');
    }

    function products(): BelongsToMany {
        return $this->belongsToMany('App\Models\Product', 'customer_product')->using('App\Models\Portfolio')->withTimestamps()->withPivot(['foreign_id']);
    }

    function synchronizePortfolio() {

        $storeEngine = $this->store->storeEngine;
        $storeEngine->synchronizePortfolio($this);

    }

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    function updateNumberUsers() {
        $this->number_users=$this->users()->count();
        $this->save();
    }

    function updateNumberPortfolioProducts() {
        $this->number_portfolio_products=$this->products()->count();
        $this->save();
    }

}
