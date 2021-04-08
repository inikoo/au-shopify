<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 13:01:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Helpers\SyncOps\Shopify;

use Illuminate\Support\Arr;

trait ShopifyProductOps {

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


            $title=Arr::pull($variantData, 'title');
            if($title=='Default Title'){
                $title=$this->title;
            }

            //print_r($variantData);

            $variant = $this->variants()->updateOrCreate(
                [
                    'id' => Arr::pull($variantData, 'id'),

                ], [
                    'sku'                  => Arr::pull($variantData, 'sku'),
                    'title'                => $title,
                    'inventory_item_id'    => Arr::pull($variantData, 'inventory_item_id'),
                    'fulfillment_service'  => Arr::pull($variantData, 'fulfillment_service'),
                    'inventory_management' => Arr::pull($variantData, 'inventory_management'),
                    'barcode'              => Arr::pull($variantData, 'barcode'),
                    'data'                 => [],
                ]
            );

            if ($variant->wasRecentlyCreated) {
                $variant->link_status = (!$this->user->customer_id ? 'unknown' : 'external');
                $variant->save();
            }


        }

    }
}

