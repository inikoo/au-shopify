<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 26 Mar 2021 14:15:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;

/**
 * Class User
 *
 * @property integer                           $id
 * @property integer                           $customer_id
 * @property string                            $name
 * @property string                            $state
 * @property integer                           $number_shopify_products
 * @property integer                           $number_shopify_variants
 * @property integer                           $number_linked_shopify_variants
 * @property array                             $data
 * @property array                             $settings
 * @property array                             $stats
 * @property \App\Models\Customer              $customer
 * @property \App\Models\ShopifyProductVariant $shopify_product_variants
 * @mixin \Eloquent
 */
class User extends Authenticatable implements IShopModel {
    use HasFactory, Notifiable;
    use ShopModel;


    protected $attributes = [
        'stats'    => '{}',
        'data'     => '{}',
        'settings' => '{}',
    ];


    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'stats'             => 'array',
        'data'              => 'array',
        'settings'          => 'array',
        'email_verified_at' => 'datetime',
    ];

    /**
     * @var mixed
     */

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function shopify_products(): HasMany {
        return $this->hasMany('App\Models\ShopifyProduct');
    }

    public function shopify_product_variants(): HasManyThrough {
        return $this->hasManyThrough(ShopifyProductVariant::class, ShopifyProduct::class);
    }

    function portfolioItems() {
        return $this->hasMany(UserPortfolioItem::class);
    }


    function updateStats() {
        $this->updateProductsStats();
        $this->updatePortfolioStats();

    }


    function updateProductsStats() {
        $stats = $this->stats;


        $shopifyStoreProductsLinkStatus = [
            'external' => 0,
            'unknown'  => 0,
            'possible' => 0,
            'linked'   => 0,
            'orphan'   => 0,
        ];

        $results = DB::table('shopify_product_variants')->select('shopify_product_variants.link_status', DB::raw('count(*) as num'))->leftJoin('shopify_products', 'shopify_product_variants.shopify_product_id', '=', 'shopify_products.id')->where(
            'shopify_products.user_id', '=', $this->id
        )->groupBy('shopify_product_variants.link_status')->get();


        foreach ($results as $row) {
            $shopifyStoreProductsLinkStatus[$row->link_status] = $row->num;
        }


        data_set($stats, 'products.link_status', $shopifyStoreProductsLinkStatus);

        $this->stats = $stats;
        $this->save();
    }

    function updatePortfolioStats() {
        $stats = $this->stats;


        $shopifyStorePortfolioItemStatus = [
            'linked' => 0,
            'unlinked'  => 0,

        ];

        $results = DB::table('user_portfolio_items')->select('user_portfolio_items.status', DB::raw('count(*) as num'))->where(
            'user_portfolio_items.user_id', '=', $this->id
        )->groupBy('user_portfolio_items.status')->get();


        foreach ($results as $row) {
            $shopifyStorePortfolioItemStatus[$row->status] = $row->num;
        }


        data_set($stats, 'portfolio.link_status', $shopifyStorePortfolioItemStatus);

        $this->stats = $stats;
        $this->save();
    }


    function createWebhooks() {
        foreach (config('shopify-app.webhooks') as $webhookConfig) {
            $this->api()->rest(
                'POST', '/admin/webhooks.json', [
                          'webhook' => [
                              'topic'   => $webhookConfig['topic'],
                              'address' => $webhookConfig['address'],
                              'format'  => 'json'
                          ]
                      ]
            );


        }
    }

    function synchronizeStore() {
        $request = $this->api()->rest('GET', '/admin/shop.json');

        if (data_get($request, 'status') == 200) {

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
        if ($this->customer->id) {
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
