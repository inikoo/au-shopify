<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 23:40:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property integer $id
 * @property string $link_status
 * @property integer user_portfolio_item_id
 * @property integer sku
 * @property \App\Models\ShopifyProduct shopifyProduct
 * @mixin \Eloquent
 */
class ShopifyProductVariant extends Model {

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];


    protected $guarded = [];

    protected $appends = [
        'formatted_link_status',
        'action'
    ];

    /** @noinspection PhpUnused */
    public function getActionAttribute(): string {

        switch ($this->link_status) {
            case 'engaged':
                return sprintf(
                    '<span x-data="linkShopifyProduct(%d)"><button @click="submitAction($dispatch)" type="button" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
  <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
</svg>
  %s
</button></span>', $this->id, __('Link product')
                );

            default:
                return '';
        }

    }

    /** @noinspection PhpUnused */
    public function getFormattedLinkStatusAttribute(): string {

        switch ($this->link_status) {
            case 'unknown':
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', __('Unlinked'));
            case 'linked':
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-800">%s</span>', __('Linked'));

            default:
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', $this->link_status);
        }

    }

    public function shopifyProduct(): BelongsTo {
        return $this->belongsTo(ShopifyProduct::class);
    }


    public function updateLinkStatus() {

        if ($this->link_status == 'linked') {
            return;
        }

        $this->link_status = 'unlinked';
        if ($this->user_portfolio_item_id) {
            $this->link_status = 'linked';
        } elseif ($this->sku) {

            if (DB::table('user_portfolio_items')->where('code', $this->sku)->where('status', 'unlinked')->where('user_id', $this->shopifyProduct->user->id)->count()) {
                $this->link_status = 'engaged';
            }


        }
        $this->save();

    }


}
