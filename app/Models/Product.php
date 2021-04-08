<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 23 Mar 2021 23:27:09 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property mixed   store
 * @mixin \Eloquent
 */
class Product extends Model {
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
                'code',
                'store_id'
            ]
        )->saveSlugsTo('slug');
    }

    public function store(): BelongsTo {
        return $this->belongsTo(Store::class);
    }

    public function images(): MorphMany {
        return $this->morphMany('App\Models\Helpers\ImageModel', 'image_models', 'imageable_type', 'imageable_id');
    }

    function customers(): BelongsToMany {
        return $this->belongsToMany('App\Models\Customer', 'portfolio')->using('App\Models\PortfolioItem')->withTimestamps()->withPivot(['foreign_id']);
    }

}
