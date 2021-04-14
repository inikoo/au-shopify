<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 14 Apr 2021 02:16:20 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Http\Controllers;


use App\Jobs\AfterRegisterCustomerJob;
use App\Models\Helpers\Image;
use Arr;
use finfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller {

    function display($checksum) {


        $image=Image::firstWhere('checksum',$checksum);
        if($image){
           // dd(Arr::get($image->communalImage->imageable->data,'mime'));

           // return response($image->communalImage->imageable->image_data);



            $mime=Arr::get($image->communalImage->imageable->data,'mime');
            $imageRawData=$image->communalImage->imageable->image_data;
            $my_bytea = stream_get_contents($imageRawData);
            $my_string = pg_unescape_bytea($my_bytea);


          // header("Content-type: $mime");
          //  echo $my_string;
            return response()->make($my_string, 200, array(
                'Content-Type' => $mime
            ));

        }

    }


}
