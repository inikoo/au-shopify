<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 01 Apr 2021 14:39:33 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property integer              $id
 * @property string               $status
 * @property \App\Models\Customer $customer
 * @property \App\Models\Product  $product
 */
class UserPortfolioItem extends Model {
    use softDeletes;


    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $appends = ['formatted_status','action'];

    protected $guarded = [];


    /** @noinspection PhpUnused */
    public function getFormattedStatusAttribute(): string {

        switch ($this->status){
            case 'unlinked':
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', __('Unlinked'));
            case 'linked':
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-green-100 text-green-400">%s</span>', __('Unlinked'));

            default:
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', $this->status);
        }

    }


    /** @noinspection PhpUnused */
    public function getActionAttribute(): string {

        switch ($this->status){
            case 'unlinked':
                return sprintf('<span x-data="createShopifyProduct(%d)"><button @click="submitAction($dispatch)" type="button" class="inline-flex items-center px-3 py-2 border border-transparent shadow-sm text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
  <svg xmlns="http://www.w3.org/2000/svg" class="-ml-0.5 mr-2 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
</svg>
  %s
</button></span>', $this->id,__('Create product'));

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


}
