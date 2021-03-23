<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 23 Mar 2021 15:54:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Engines;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AuroraStoreEngine
 *
 * @property integer $id
 * @property string  $slug*
 * @property array   $data
 * @method static firstOrCreate(string[] $array)
 */
class AuroraStoreEngine extends Model {

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];



}
