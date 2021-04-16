<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Wed, 24 Mar 2021 14:16:58 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create(
            'images', function (Blueprint $table) {
            $table->id();
            $table->string('checksum')->nullable()->index();
            $table->string('mime');
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();

        }
        );

        Schema::create(
            'image_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_id');
            $table->foreign('image_id')->references('id')->on('images');
            $table->string('url');
            $table->string('imageable_type')->nullable()->index();
            $table->unsignedBigInteger('imageable_id')->nullable()->index();

            $table->string('scope')->index();
            $table->smallInteger('precedence')->default(0);
            $table->jsonb('data');
            $table->unsignedInteger('foreign_id')->index();

            $table->timestampsTz();
            $table->index(
                [
                    'imageable_id',
                    'imageable_type',
                    'scope'
                ]
            );
            $table->unique(
                [
                    'image_id',
                    'imageable_id',
                    'imageable_type',
                    'scope'
                ]
            );

        }
        );

        Schema::create(
            'products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->unsignedInteger('foreign_id')->index();
            $table->string('slug')->nullable()->index();
            $table->string('code')->index();
            $table->string('state')->nullable()->index();
            $table->boolean('status')->nullable()->index();
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('unit_price');
            $table->unsignedMediumInteger('units');
            $table->unsignedMediumInteger('available')->default(0)->nullable();
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();


        }
        );

        Schema::create(
            'portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('foreign_id')->index();
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(
                [
                    'customer_id',
                    'product_id',
                ]
            );

        }
        );

        Schema::create(
            'user_portfolio_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('portfolio_item_id')->constrained();
            $table->foreignId('product_id')->nullable()->constrained();
            $table->string('code')->nullable()->collation('case_insensitive');
            $table->string('name')->nullable();
            $table->string('status')->default('unlinked')->index();
            $table->jsonb('data');
            $table->timestampsTz();
            $table->softDeletesTz();
            $table->unique(
                [
                    'user_id',
                    'portfolio_item_id',
                ]
            );

        }
        );

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('user_portfolio_items');
        Schema::dropIfExists('portfolio_items');
        Schema::dropIfExists('products');
        Schema::dropIfExists('image_models');
        Schema::dropIfExists('images');
    }
}
