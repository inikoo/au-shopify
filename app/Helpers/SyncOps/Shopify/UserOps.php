<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 07 Apr 2021 12:27:31 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Helpers\SyncOps\Shopify;

use Illuminate\Support\Arr;

trait UserOps {


    function synchronizeStore() {
        $request = $this->api()->rest('GET', '/admin/shop.json');

        if (in_array(data_get($request, 'status') ,[200,201])) {

            $data       = $this->data;
            $this->data = data_set($data, 'shopify', data_get($request, 'body.shop'));
            $this->save();

        }
    }

    function synchronizeProducts() {
        $request = $this->api()->rest('GET', '/admin/products.json');
        if (data_get($request, 'status') == 200) {


            $productsData = data_get($request, 'body.products.container');

            /**
             * Removing deleted products
             */
            $currentShopifyProductsIDs = $this->shopify_products()->pluck('id')->all();
            $shopifyProductsIDs        = Arr::pluck($productsData, 'id');
            $shopifyProductsToDelete   = array_diff($currentShopifyProductsIDs, $shopifyProductsIDs);
            $this->shopify_products()->whereIn('id', $shopifyProductsToDelete)->delete();

            foreach ($productsData as $productData) {
                $this->synchronizeProduct($productData);


            }
        }
    }

    function synchronizeProduct($productData) {

        $variants = Arr::pull($productData, 'variants', []);

        /**
         * @var $shopify_product \App\Models\ShopifyProduct
         */
        $shopify_product = $this->shopify_products()->updateOrCreate(
            [
                'id' => Arr::pull($productData, 'id'),

            ], [
                'status' => Arr::pull($productData, 'status', 'limbo'),
                'title'  => Arr::pull($productData, 'title'),

                'data' => $productData,
            ]
        );

        $shopify_product->synchronizeVariants($variants);
    }

    function synchronizePortfolio() {
        if ($this->customer) {
            foreach ($this->customer->portfolioItems()->get() as $portfolioItem) {

                $this->portfolioItems()->updateOrCreate(
                    [
                        'portfolio_item_id' => $portfolioItem->id,
                    ], []
                );

            }
        }
    }

}

