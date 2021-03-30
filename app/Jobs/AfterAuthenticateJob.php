<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 15:22:34 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct()
    {
        //
    }


    public function handle() {
        $user=Auth::user();
        $user->synchronize();

    }
}
