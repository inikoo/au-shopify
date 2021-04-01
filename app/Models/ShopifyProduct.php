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
 * @property integer          $id
 * @property \App\Models\User $user
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

    function synchronizeVariants($variantsData) {

        /**
         * Removing deleted variants
         */
        $currentVariantsIDs = $this->variants()->pluck('id')->all();
        $variantsIDs        = Arr::pluck($variantsData, 'id');
        $variantsToDelete   = array_diff($currentVariantsIDs, $variantsIDs);
        $this->variants()->whereIn('id', $variantsToDelete)->delete();


        foreach ($variantsData as $variantData) {

            $variantData = Arr::except($variantData, ['product_id']);

            /**
             * @var \App\Models\ShopifyProductVariant $variant
             */
            $variant = $this->variants()->updateOrCreate(
                [
                    'id' => Arr::pull($variantData, 'id'),

                ], [
                    'sku'                  => Arr::pull($variantData, 'sku'),
                    'title'                => Arr::pull($variantData, 'title'),
                    'inventory_item_id'    => Arr::pull($variantData, 'inventory_item_id'),
                    'fulfillment_service'  => Arr::pull($variantData, 'fulfillment_service'),
                    'inventory_management' => Arr::pull($variantData, 'inventory_management'),
                    'barcode'              => Arr::pull($variantData, 'barcode'),
                    'data'                 => $variantData,
                ]
            );

            if ($variant->wasRecentlyCreated) {
                $variant->link_status = (!$this->user->customer_id ? 'unknown' : 'external');
                $variant->save();
            }


        }

    }

}
