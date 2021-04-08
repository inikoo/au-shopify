<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 23:40:22 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;


/**
 * @property integer $id
 * @property string  $link_status
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

    protected $appends = ['formatted_link_status'];

    /** @noinspection PhpUnused */
    public function getFormattedLinkStatusAttribute(): string {

        switch ($this->link_status){
            case 'unknown':
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', __('Unlinked'));

            default:
                return sprintf('<span class="inline-flex capitalize items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-400">%s</span>', $this->link_status);
        }

    }



    public function product(): HasOneThrough {
        return $this->HasOneThrough(PortfolioItem::class, Product::class);
    }

    /*
    public function calculateLinkStatus(){
        if($this->link_status=='unknown'){
           // $portfolio=
        }
    }
    */

}
