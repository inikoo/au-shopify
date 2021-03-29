<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 27 Mar 2021 11:24:44 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Models\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;

/**
 *
 * @property integer $id
 * @property integer communal_image_id
 * @property string  checksum
 * @mixin \Eloquent
 *
 */
class Image extends Model {


    protected $casts = [
        'data' => 'array'
    ];

    protected $attributes = [
        'data' => '{}',
    ];

    protected $guarded = [];

    public function models(): HasMany {
        return $this->hasMany('App\Models\Helpers\ImageModel');
    }

    public function saveImage($image_filename_data) {

        $imagePath = $image_filename_data['image_path'];

        $size_data = getimagesize($imagePath);
        $width     = $size_data[0];
        $height    = $size_data[0];


        $data = [
            'mime'     => Arr::get($size_data, 'mime', $image_filename_data['mime']),
            'bits'     => Arr::get($size_data, 'bits'),
            'channels' => Arr::get($size_data, 'channels'),
            'width'    => $width,
            'height'   => $height,
        ];
        $data = array_filter($data);

        $originalImage = (new OriginalImage)->firstOrCreate(
            ['checksum' => md5_file($imagePath)], [
                                                    'filesize'   => filesize($imagePath),
                                                    'megapixels' => $width * $height / 1000000,
                                                    'image_data' => pg_escape_bytea(file_get_contents($imagePath)),
                                                    'data'       => $data
                                                ]
        );


        if (!$originalImage->communalImage) {
            $originalImage->communalImage()->save(new CommunalImage());

        }

        $this->communal_image_id = (new OriginalImage)->find($originalImage->id)->communalImage->id;


        $this->checksum = $originalImage->checksum;
        $this->save();


    }

}
