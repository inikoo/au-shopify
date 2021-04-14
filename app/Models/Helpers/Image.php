<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 27 Mar 2021 11:24:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

/**
 *
 * @property integer $id
 * @property integer communal_image_id
 * @property string  checksum
 * @mixin \Eloquent
 *
 */
class Image extends Model {


    protected $casts = [
        'data' => 'array'
    ];


    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];



}
