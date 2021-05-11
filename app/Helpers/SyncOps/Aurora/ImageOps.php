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


    function getAuroraImagesData($params): array {

        $imageModelData = [];

        $limit = '';
        if (!empty($params['limit'])) {
            $limit = ' limit '.$params['limit'];
        }

        $sql = "* from `Image Subject Bridge` B  left join `Image Dimension` I on (`Image Subject Image Key`=`Image Key`) where  `Image Subject Object`=?  and `Image Subject Object Key`=?  ORDER BY FIELD(`Image Subject Is Principal`, 'Yes','No') $limit";
        foreach (
            DB::connection('aurora')->select(
                "select $sql",

                [
                    $params['objectType'],
                    $params['object']->foreign_id
                ]
            ) as $imageAuroraData
        ) {


            $image = (new Image)->updateOrCreate(
                [
                    'checksum' => $imageAuroraData->{'Image File Checksum'},
                ], [
                    'mime'=> $imageAuroraData->{'Image MIME Type'},
                    'data'     => [
                        'width'  => $imageAuroraData->{'Image Width'},
                        'height' => $imageAuroraData->{'Image Height'},
                        'size'   => $imageAuroraData->{'Image File Size'},
                    ]
                ]
            );

            $imageAuroraData->image_id                = $image->id;
            $imageAuroraData->url                     = 'https://'.$params['object']->store->url.'/wi.php?id='.$imageAuroraData->{'Image Key'};
            $imageAuroraData->store_id                = $params['object']->store->id;
            $imageAuroraData->foreign_id              = $imageAuroraData->{'Image Subject Key'};
            $imageAuroraData->common_image_foreign_id = $imageAuroraData->{'Image Key'};
            $imageAuroraData->caption                 = $imageAuroraData->{'Image Subject Image Caption'};

            $imageModelData[] = $imageAuroraData;


        }

        return $imageModelData;

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


            $scope = $get_scope($imageModelData->{'Image Subject Object Image Scope'});


            $data = ['common_image_foreign_id' => $imageModelData->common_image_foreign_id];

            if ($imageModelData->caption) {
                $data['caption'] = $imageModelData->caption;
            }

            $imageModel          = (new ImageModel)->updateOrCreate(
                [
                    'imageable_type' => $model->getMorphClass(),
                    'imageable_id'   => $model->id,
                    'scope'          => $scope,
                    'image_id'       => $imageModelData->image_id,

                ], [
                    'data'       => $data,
                    'precedence' => $precedence,
                    'url'        => $imageModelData->url,
                    'foreign_id' => $imageModelData->foreign_id
                ]
            );

            $new_imageModelIds[] = $imageModel->id;
            $model->images()->save($imageModel);
            $precedence--;

        }
        $model->images()->whereIn('id', array_diff($old_imageModelIds, $new_imageModelIds))->delete();



    }


}

