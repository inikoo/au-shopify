<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:28:53 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Helpers\SyncOps\Aurora;

use Illuminate\Support\Facades\DB;

trait ProductOps {

    public function synchronizeProducts() {

        $store=$this->owners['store'];
        $bar=false;

        if ($this->showBar) {
            $sql = "count(*) as num from `Product Dimension` where `Product Store Key`=?";

            $count_products_data = DB::connection('aurora')->select("select $sql ", [$store->foreign_id])[0];


            $bar = $this->showBar->createProgressBar($count_products_data->num);
            $bar->setFormat('debug');
        }

        $sql = ' * from `Product Dimension` where `Product Store Key`=?';
        foreach (DB::connection('aurora')->select("select $sql", [$store->foreign_id]) as $foreignProduct) {


            $available = $foreignProduct->{'Product Availability'};
            $available = (integer)floor($available);
            if (!$available or $available < 0) {
                $available = 0;
            }


            $status = true;
            if ($foreignProduct->{'Product Status'} == 'Discontinued') {
                $status = false;
            }

            $units = $foreignProduct->{'Product Units Per Case'};
            if ($units == 0) {
                $units = 1;
            }

            if ($foreignProduct->{'Product Valid From'} == '0000-00-00 00:00:00') {
                $created_at = null;
            } else {
                $created_at = $foreignProduct->{'Product Valid From'};
            }


            switch ($foreignProduct->{'Product Status'}) {
                case 'InProcess':
                    $state = 'creating';
                    break;
                case 'Discontinuing':
                    $state = 'discontinuing';
                    break;
                case 'Discontinued':
                    $state = 'discontinued';
                    break;
                default:
                    $state = 'active';
            }


            $product = $this->createProduct(
                $store, $foreignProduct->{'Product ID'}, [
                          'state'      => $state,
                          'status'     => $status,
                          'name'       => $foreignProduct->{'Product Name'},
                          'code'       => $foreignProduct->{'Product Code'},
                          'units'      => $units,
                          'available'  => $available,
                          'unit_price' => $foreignProduct->{'Product Price'} / $units,
                          'created_at' => $created_at

                      ]

            );

            $this->synchronizeProductImages($product);


            $data=$product->data;
            data_set($data,'body_html',$foreignProduct->{'Product Published Webpage Description'});
            data_set($data,'grams',$foreignProduct->{'Product Unit Weight'});
            data_set($data,'rrp',$foreignProduct->{'Product RRP'} / $units);
            data_set($data,'barcode',$foreignProduct->{'Product Barcode Number'});





            $product->data=$data;
            $product->save();




            if ($bar) {
                $bar->advance();
            }



            $sql = "`Product Dimension` set `Product Shopify Key`=? where `Product ID`=?";
            DB::connection('aurora')->statement(
                "update $sql", [
                                 $product->id,
                                 $foreignProduct->{'Product ID'}
                             ]
            );


        }

        if ($bar) {
            $bar->finish();
            print "\n";
        }

    }


    private function createProduct($store, $foreignID, $data) {
        return $store->products()->updateOrCreate(
            [
                'foreign_id' => $foreignID,
            ], $data
        );
    }

    private function synchronizeProductImages($product){


        $imagesModelData = $this->getAuroraImagesData(
            [
                'objectType'     => 'Product',
                'object' => $product,

            ]
        );


        $this->syncImages($product,$imagesModelData, function ($_scope){
            $scope = 'marketing';
            if ($_scope== '') {
                $scope = 'marketing';
            }
            return $scope;
        });



    }

}

