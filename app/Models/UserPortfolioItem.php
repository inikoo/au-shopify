<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 14:39:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Arr;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer              $id
 * @property string               $status
 * @property \App\Models\Customer $customer
 * @property \App\Models\Product  $product
 * @property \App\Models\User     $user
 * @property integer              shopify_product_variant_id
 * @property string               formatted_status
 * @property string               action
 * @mixin \Eloquent
 *
 */
class UserPortfolioItem extends Model {
    use softDeletes;


    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $appends = [
        'formatted_status',
        'action'
    ];

    protected $guarded = [];


    /** @noinspection PhpUnused */
    public function getFormattedStatusAttribute(): string {

        switch ($this->status) {
            case 'unlinked':
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', __('Unlinked'));
            case 'linked':
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">%s</span>', __('Linked'));

            default:
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', $this->status);
        }

    }


    /** @noinspection PhpUnused */
    public function getActionAttribute(): string {

        switch ($this->status) {
            case 'unlinked':
                return sprintf(
                    '<span x-data="createShopifyProduct(%d)"><button @click="submitAction($dispatch)" type="button" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
  <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
</svg>
  %s
</button></span>', $this->id, __('Create product')
                );

            default:
                return '';
        }

    }

    public function user(): BelongsTo {
        return $this->belongsTo('App\Models\User');
    }

    public function portfolioItem(): BelongsTo {
        return $this->belongsTo('App\Models\PortfolioItem');
    }

    public function product(): BelongsTo {
        return $this->belongsTo('App\Models\Product');
    }

    public function createShopifyProduct(): object {

        $result = (object)[
            'success' => false
        ];


        $images = [];
        foreach ($this->product->images->pluck('url')->all() as $imageSrc) {
            $images[] = ['src' => $imageSrc];
        }


        $productData = [
            'product' => [
                'title'     => $this->product->name,
                'body_html' => Arr::get($this->product->data, 'body_html'),
                'published' => data_get($this->user->settings, 'product.published', false),

                'images'   => $images,
                'variants' => [
                    [
                        'sku'                 => $this->product->code,
                        'weight_unit'         => 'g',
                        'weight'              => Arr::get($this->product->data, 'grams'),
                        'fulfillment_service' => Arr::get($this->user->data, 'fulfillment_service.handle')
                    ]
                ]
            ]
        ];


        $response = Auth::user()->api()->rest(
            'POST', '/admin/products.json', $productData
        );


        if ($response['status'] == 201) {
            $result->success = true;

            // print_r($response);

            /**
             * @var $shopify_product \App\Models\ShopifyProduct
             */
            $shopify_product = $this->user->shopify_products()->updateOrCreate(
                [
                    'id' => data_get($response, 'body.container.product.id'),

                ], [
                    'status' => 'linked',
                    'title'  => data_get($response, 'body.container.product.title'),

                    // 'data' => $productData,
                ]
            );
            /**
             * @var $variant \App\Models\ShopifyProductVariant
             */
            $variant                          = $shopify_product->variants()->updateOrCreate(
                [
                    'id' => data_get($response, 'body.container.product.variants.0.id'),

                ], [
                    'sku'                    => data_get($response, 'body.container.product.variants.0.sku'),
                    'title'                  => data_get($response, 'body.container.product.variants.0.title'),
                    'inventory_item_id'      => data_get($response, 'body.container.product.variants.0.inventory_item_id'),
                    'fulfillment_service'    => data_get($response, 'body.container.product.variants.0.fulfillment_service'),
                    'inventory_management'   => data_get($response, 'body.container.product.variants.0.inventory_management'),
                    'barcode'                => data_get($response, 'body.container.product.variants.0.barcode'),
                    'data'                   => [],
                    'user_portfolio_item_id' => $this->id,
                    'link_status'            => 'linked'
                ]
            );
            $this->shopify_product_variant_id = $variant->id;
            $this->status                     = 'linked';

            $this->save();
            $this->user->updateStats();
            $result->formatted_status = $this->formatted_status;
            $result->action           = $this->action;
            $result->shopify_products = Arr::get($this->user->stats, 'products.total', '0');
            $result->linked_products  = Arr::get($this->user->stats, 'products.link_status.linked', '0');
            $result->portfolio_items  = Arr::get($this->user->stats, 'portfolio.total', '0');


        }


        return $result;

    }

}
