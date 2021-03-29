<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 13 Oct 2020 02:29:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2020. Aiku.io
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 *
 * @property integer $id
 *
 */
class ProcessedImage extends Pivot {

    protected $table = 'original_images';
    protected $connection= 'master';

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}'
    ];
    protected $guarded =[];

    public function communalImage(): MorphOne {
        return $this->morphOne('App\Models\Helpers\CommunalImage', 'imageable');
    }

}
