<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 31 Mar 2021 16:40:27 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use stdClass;

class CreateProductJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $shopDomain;
    public $data;


    public function __construct(string $shopDomain, stdClass $data)
    {
        $this->shopDomain = $shopDomain;
        $this->data = $data;
    }

    public function handle()
    {
        $user = User::firstWhere('name', $this->shopDomain);
        if ($user->id) {
            $productData = json_decode(json_encode($this->data), true);
            $user->synchronizeProduct($productData);
            $user->updateStats();
        }
    }
}


