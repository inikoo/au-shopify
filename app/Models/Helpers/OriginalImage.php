<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Mon, 12 Oct 2020 23:03:42 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 *
 * @property integer                           $id
 * @property string                            $checksum
 * @property \App\Models\Helpers\CommunalImage $communalImage
 * @mixin \Eloquent
 */
class OriginalImage extends Model {

    protected $table = 'original_images';
    protected $connection = 'master';

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}'
    ];
    protected $guarded = [];

    public function communalImage(): MorphOne {
        return $this->morphOne('App\Models\Helpers\CommunalImage', 'imageable');
    }


}
