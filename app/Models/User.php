<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 26 Mar 2021 14:15:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use App\Helpers\SyncOps\Shopify\UserOps;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
    use UserOps;


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


    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
    }

    public function shopify_products(): HasMany {
        return $this->hasMany('App\Models\ShopifyProduct');
    }

    public function shopify_product_variants(): HasManyThrough {
        return $this->hasManyThrough(ShopifyProductVariant::class, ShopifyProduct::class);
    }

    function portfolioItems(): HasMany {
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

        data_set(
            $stats, 'products.total', $shopifyStoreProductsLinkStatus['external'] + $shopifyStoreProductsLinkStatus['unknown'] + $shopifyStoreProductsLinkStatus['possible'] + $shopifyStoreProductsLinkStatus['linked'] + $shopifyStoreProductsLinkStatus['orphan']

        );
        data_set($stats, 'products.link_status', $shopifyStoreProductsLinkStatus);

        $this->stats = $stats;
        $this->save();
    }

    function updatePortfolioStats() {
        $stats = $this->stats;


        $shopifyStorePortfolioItemStatus = [
            'linked'   => 0,
            'unlinked' => 0,

        ];

        $results = DB::table('user_portfolio_items')->select('user_portfolio_items.status', DB::raw('count(*) as num'))->where(
            'user_portfolio_items.user_id', '=', $this->id
        )->groupBy('user_portfolio_items.status')->get();


        foreach ($results as $row) {
            $shopifyStorePortfolioItemStatus[$row->status] = $row->num;
        }

        data_set($stats, 'portfolio.total', $shopifyStorePortfolioItemStatus['linked'] + $shopifyStorePortfolioItemStatus['unlinked']);
        data_set($stats, 'portfolio.link_status', $shopifyStorePortfolioItemStatus);

        $this->stats = $stats;
        $this->save();
    }

    /**
     * Create webhooks and fulfillment service
     */
    function setupShopifyStore() {

       // dd($this->data);
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

        $request=$this->api()->rest(
            'POST', '/admin/fulfillment_services.json', [
                      'fulfillment_service' => [
                          'name'   => $this->customer->store->name,
                          'callback_url'   => config('app.url').'/webhook/fulfillment-services',
                          'inventory_management'=>true,
                          'tracking_support'=>true,
                          'requires_shipping_method'=>true,
                          'format'=>'json'



                      ]
                  ]
        );
        if (data_get($request, 'status')==201) {
            $data = $this->data;
            data_set($data, 'fulfillment_service.id', data_get($request, 'body.fulfillment_service.id'));
            data_set($data, 'fulfillment_service.provider_id', data_get($request, 'body.fulfillment_service.provider_id'));
            data_set($data, 'fulfillment_service.location_id', data_get($request, 'body.fulfillment_service.location_id'));
            $this->data = $data;
            $this->save();
        }



    }


}
