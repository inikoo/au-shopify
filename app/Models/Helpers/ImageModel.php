<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 27 Mar 2021 11:24:35 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 *
 * @property int $id
 * @mixin \Eloquent
 */
class ImageModel extends Pivot {

    protected $table = 'image_models';

    protected $casts = [
        'data' => 'array'
    ];


    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function image(): BelongsTo {
        return $this->belongsTo('App\Models\Helpers\Image');
    }

    public function model(): MorphTo {
        return $this->morphTo(__FUNCTION__, 'imageable_type', 'imageable_id');
    }

}
