<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 16 Mar 2021 14:21:57 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use stdClass;

/**
 * Class Store
 *
 * @property integer $id
 * @property integer $foreign_id
 * @property string  $name
 * @property string  $url
 * @property string  $slug
 * @property string  $currency
 * @property string  $locale

 * @property array   $data
 * @property \App\Models\StoreEngine   storeEngine
 * @method static where(string $string, array|string|null $argument)
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

    public function saveStoreEngineToken(): string {

        $token=$this->createToken('DirectLink')->plainTextToken;

        $this->storeEngine->saveStoreEngineToken($token,$this->id);

        return $token;
    }

    public function customers(): HasMany {
        return $this->hasMany('App\Models\Customer');
    }

    public function products(): HasMany {
        return $this->hasMany('App\Models\Product');
    }

    public function storeEngine(): BelongsTo {
        return $this->belongsTo(StoreEngine::class);
    }

    public function synchronizeProducts($bar=false) {
        $this->storeEngine->synchronizeProducts($this,$bar);
    }

    public function registerCustomer($data): stdClass {
        return $this->storeEngine->registerCustomer($this,$data);

    }



}
