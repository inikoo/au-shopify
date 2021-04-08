<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 27 Mar 2021 12:03:11 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Helpers\SyncOps\Aurora;

use App\Models\Helpers\Image;
use App\Models\Helpers\ImageModel;
use Illuminate\Support\Facades\DB;

trait ImageOps {


    function getAuroraImageFilename($imageAuroraData) {


        $image_path = sprintf(
                config('app.aurora.images_path').$this->owners['storeEngine']->data['code']
            ).'/db/'.$imageAuroraData->{'Image File Checksum'}[0].'/'.$imageAuroraData->{'Image File Checksum'}[1].'/'.$imageAuroraData->{'Image File Checksum'}.'.'.$imageAuroraData->{'Image File Format'};

        if (file_exists($image_path)) {
            return [
                'image_path' => $image_path,
                'filename'   => $imageAuroraData->{'Image Filename'},
                'mime'       => $imageAuroraData->{'Image MIME Type'}
            ];
        } else {
            return false;
        }

    }


    /*
    function getImageData($foreignImageID) {
        $sql = "* from `Image Dimension` I  where `Image Key`=?";
        foreach (
            DB::connection('aurora')->select(
                "select $sql ", [$foreignImageID]
            ) as $imageAuroraData
        ) {
            $image_filename_data=getAuroraImageFilename($imageAuroraData);
            if ($image_filename_data) {
                return create_image_from_legacy$imageAuroraData,$image_filename_data);
            }

        }

        return false;

    }
*/

    function getAuroraImagesData($params): array {

        $imageModelData = [];

        $limit = '';
        if (!empty($params['limit'])) {
            $limit = ' limit '.$params['limit'];
        }

        $sql = "* from `Image Subject Bridge` B  left join  `Image Dimension` I on (`Image Subject Image Key`=`Image Key`) where  `Image Subject Object`=?  and `Image Subject Object Key`=?  ORDER BY FIELD(`Image Subject Is Principal`, 'Yes','No') $limit";
        foreach (
            DB::connection('aurora')->select(
                "select $sql",

                [
                    $params['object'],
                    $params['object_key']
                ]
            ) as $imageAuroraData
        ) {


            $auroraImageFilename = $this->getAuroraImageFilename($imageAuroraData);

            if ($auroraImageFilename) {
                $imageModelData[] = $this->createImageFromAurora($imageAuroraData, $auroraImageFilename);
            }

        }

        return $imageModelData;

    }


    function createImageFromAurora($imageAuroraData, $auroraImageFilename): array {


        $imageData = $this->fillAuroraData(
            [
                'mime_type' => 'Image MIME Type',
                'width'     => 'Image Width',
                'height'    => 'Image Height',


            ], $imageAuroraData
        );




        $image = (new Image)->updateOrCreate(
            [
                'foreign_id' => $imageAuroraData->{'Image Key'},
                'store_id'   => $this->owners['store']->id
            ], [
                'created_at' => $imageAuroraData->{'Image Creation Date'},
                'data'       => $imageData
            ]
        );

        if (!$image->communal_image_id) {
            $image->saveImage($auroraImageFilename);

        }

        return [
            'image_id' => $image->id,
            'scope'    => (isset($imageAuroraData->{'Image Subject Object Image Scope'}) ? $imageAuroraData->{'Image Subject Object Image Scope'} : ''),
            'data'     => [
                'filename' => $auroraImageFilename['filename']
            ]
        ];


    }

    function syncImages($model, $imagesModelData, $get_scope) {

        $old_imageModelIds = [];
        $new_imageModelIds = [];

        $model->images()->get()->each(
            function ($imageModel) use (&$old_imageModelIds) {
                $old_imageModelIds[] = $imageModel->id;
            }
        );
        $precedence = 1;
        foreach ($imagesModelData as $imageModelData) {


            $scope = $get_scope($imageModelData['scope']);

            $imageModel          = (new ImageModel)->updateOrCreate(
                [
                    'imageable_type' => $model->getMorphClass(),
                    'imageable_id'   => $model->id,
                    'scope'          => $scope,
                    'image_id'       => $imageModelData['image_id'],

                ], [
                    'data'       => $imageModelData['data'],
                    'precedence' => $precedence
                ]
            );
            $new_imageModelIds[] = $imageModel->id;
            $model->images()->save($imageModel);
            $precedence--;

        }
        $model->images()->whereIn('id', array_diff($old_imageModelIds, $new_imageModelIds))->delete();


    }


}

