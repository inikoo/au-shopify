<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 16 Mar 2021 14:21:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class Store
 *
 * @property integer $id
 * @property integer $foreign_store_id
 * @property string  $name
 * @property string  $url
 *
 * @property array   $data
 */
class Store extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, HasSlug;

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()->generateSlugsFrom('name')->saveSlugsTo('slug');
    }


    protected $guarded = [];

    public function createAccessCode(): string {

        return $this->createToken('DirectLink')->plainTextToken;
    }

}
