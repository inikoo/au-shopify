<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 14 Apr 2021 02:10:34 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */


use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;



Route::get(
    '/image/{checksum}', [
                 ImageController::class,
                 'display'
             ]
)->where('checksum', '^[a-f0-9]{32}$');
