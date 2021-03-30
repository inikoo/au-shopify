<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 26 Mar 2021 14:15:38 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Traits\ShopModel;

/**
 * Class User
 *
 * @property integer              $id
 * @property integer              $customer_id
 * @property string               $name
 * @property array                $data
 * @property array                $settings
 * @property \App\Models\Customer $customer
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

    public function customer(): BelongsTo {
        return $this->belongsTo(Customer::class);
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

    /*
    function updateStats() {
    }
    */

    function synchronize() {
        $request = $this->api()->rest('GET', '/admin/shop.json');

        if (data_get($request, 'status') == 200) {

            $data       = $this->data;
            $this->data = data_set($data, 'shopify', data_get($request, 'body.shop'));
            $this->save();

        }

    }

}
