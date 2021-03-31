<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopifyProducts extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(
            'shopify_products', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->foreignId('user_id')->constrained();
            $table->string('status')->index();
            $table->string('title')->index();

            $table->json('data');
            $table->timestampsTz();

        }
        );

        Schema::create(
            'shopify_product_variants', function (Blueprint $table) {

            $table->unsignedBigInteger('id')->primary();
            $table->string('inventory_item_id')->index();

            $table->foreignId('shopify_product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('customer_product_id')->nullable()->index();
            $table->foreign('customer_product_id')->references('id')->on('customer_product');


            $table->string('title')->index();
            $table->string('fulfillment_service')->nullable()->index();
            $table->string('inventory_management')->nullable()->index();

            $table->string('sku')->index();

            $table->string('barcode')->nullable()->index();


            $table->json('data');
            $table->timestampsTz();

        }
        );


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('shopify_product_variants');
        Schema::dropIfExists('shopify_products');
    }
}
