<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 18:34:37 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;


/**
 * @property integer $id
 * @mixin \Eloquent
 */
class ShopifyProduct extends Model {

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function variants(): HasMany {
        return $this->hasMany('App\Models\ShopifyProductVariant');
    }

    function synchronizeVariants($variants) {

        /**
         * Removing deleted variants
         */
        $currentVariantsIDs = $this->variants()->pluck('id')->all();
        $variantsIDs        = Arr::pluck($variants, 'id');
        $variantsToDelete   = array_diff($currentVariantsIDs, $variantsIDs);
        $this->variants()->whereIn('id', $variantsToDelete)->delete();


        foreach ($variants as $variant) {

            $variant = Arr::except($variant, ['product_id']);


            $this->variants()->updateOrCreate(
                [
                    'id' => Arr::pull($variant, 'id'),

                ], [
                    'sku'                  => Arr::pull($variant, 'sku'),
                    'title'                => Arr::pull($variant, 'title'),
                    'inventory_item_id'    => Arr::pull($variant, 'inventory_item_id'),
                    // 'product_id'           => Arr::pull($variant, 'product_id'),
                    'fulfillment_service'  => Arr::pull($variant, 'fulfillment_service'),
                    'inventory_management' => Arr::pull($variant, 'inventory_management'),
                    'barcode'              => Arr::pull($variant, 'barcode'),


                    'data' => $variant,
                ]
            );
        }

    }

}
