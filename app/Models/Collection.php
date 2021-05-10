<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sun, 09 May 2021 23:57:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @property integer $id
 * @property string  $code
 * @property string  $name
 * @property array   $data
 * @property mixed   store
 * @mixin \Eloquent
 */
class Collection extends Model {
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


    function products(): BelongsToMany {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

}
