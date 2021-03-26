<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 23 Mar 2021 19:06:20 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * Class AccessCode
 * @property integer $customer_id
 *
 * @package App\Models
 */
class AccessCode extends Model {
    use HasSlug;
    use SoftDeletes;


    protected $guarded = [];


    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()->generateSlugsFrom(
            function () {
                return Str::random(6).'_'.Str::random(6).'_'.Str::random(6);
            }
        )->saveSlugsTo('access_code');
    }

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }


}
