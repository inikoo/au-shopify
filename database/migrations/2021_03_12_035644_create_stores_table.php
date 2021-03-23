<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Sat, 13 Mar 2021 12:46:10 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up() {

        Schema::create(
            'aurora_store_engines', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->json('data');
            $table->timestampsTz();
        }
        );

        Schema::create(
            'store_engines', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');

            $table->unsignedSmallInteger('store_engine_id')->index()->nullable();
            $table->string('store_engine_type')->index()->nullable();

            $table->json('data');
            $table->timestampsTz();
        }
        );

        Schema::create(
            'stores', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('url');

            $table->unsignedMediumInteger('foreign_store_id')->index();

            $table->foreignId('store_engine_id')->constrained();

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
        Schema::dropIfExists('stores');
        Schema::dropIfExists('store_engines');
        Schema::dropIfExists('aurora_store_engines');


    }
}
