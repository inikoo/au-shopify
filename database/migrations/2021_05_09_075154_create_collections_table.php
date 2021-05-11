<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create(
            'collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->string('slug')->nullable()->index();
            $table->string('code')->index()->collation('case_insensitive');
            $table->text('name')->collation('case_insensitive')->nullable();
            $table->text('body_html')->collation('case_insensitive')->nullable();
            $table->unsignedMediumInteger('products_number')->default(0);
            $table->jsonb('data');
            $table->unsignedInteger('foreign_id')->index();
            $table->timestampsTz();
        }
        );

        Schema::create(
            'collection_product', function (Blueprint $table) {
            $table->foreignId('collection_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->timestampsTz();
            $table->unique(
                [
                    'collection_id',
                    'product_id',
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
        Schema::dropIfExists('collection_product');
        Schema::dropIfExists('collections');
    }
}
