<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 27 Mar 2021 11:31:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;


/**
 *
 * @property int $id
 *
 */
class CommunalImage extends Pivot {

    protected $table = 'communal_images';
    protected $connection= 'master';


    protected $guarded =[];

    public function imageable(): MorphTo {
        return $this->morphTo();
    }

}
