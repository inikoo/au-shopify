<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 26 Mar 2021 14:15:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property integer              $id
 * @property integer              $customer_id
 * @property string               $name
 * @property integer              $number_shopify_products
 * @property integer              $number_shopify_variants
 * @property integer              $number_linked_number_shopify_variants
 * @property array                $data
 * @property array                $settings
 * @property \App\Models\Customer $customer
 * @property \App\Models\ShopifyProductVariant $shopify_product_variants
 * @mixin \Eloquent
 */
class User extends Authenticatable implements IShopModel {
    use HasFactory, Notifiable;
    use ShopModel;


    protected $attributes = [
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

    function verifyCustomer($request): object {

        $result = (object)[
            'success' => false,
            'reason'  => 'server-error'
        ];

        $accessCode = AccessCode::withTrashed()->firstWhere('access_code', $request->get('accessCode'));


        if ($accessCode) {
            if ($accessCode->trashed()) {
                $result->reason = 'expired-access-code';
            } else {

                $this->customer_id = $accessCode->customer_id;
                $this->save();

                $this->customer->accessCodes()->forceDelete();
                $this->customer->updateNumberUsers();

                $result->success = true;
                $result->reason  = 'verified';
            }
        } else {
            $result->reason = 'invalid-access-code';
        }

        return $result;
    }


    function updateStats() {
        $this->number_shopify_products = $this->shopify_products()->count();
        $this->number_shopify_variants = $this->shopify_product_variants()->count();
        $this->number_shopify_variants = $this->shopify_product_variants()->whereNotNull('customer_product_id')->count();

        $this->save();
    }


    function synchronize() {
        $this->synchronizeStore();
        $this->synchronizeProducts();
        $this->updateStats();
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


                /**
                 * Removing deleted variants
                 */
                $currentVariantsIDs = $shopify_product->variants()->pluck('id')->all();
                $variantsIDs        = Arr::pluck($variants, 'id');
                $variantsToDelete   = array_diff($currentVariantsIDs, $variantsIDs);
                $shopify_product->variants()->whereIn('id', $variantsToDelete)->delete();


                foreach ($variants as $variant) {

                    $variant = Arr::except($variant, ['product_id']);


                    $shopify_product->variants()->updateOrCreate(
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


    }


}
