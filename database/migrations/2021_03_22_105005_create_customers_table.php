<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('user_id')->nullable()->index();
            $table->unsignedMediumInteger('foreign_id')->index();
            $table->string('name')->index();
            $table->json('data');
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::create('access_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->foreignId('customer_id')->index();
            $table->string('access_code')->nullable()->index();
            $table->json('data');
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_codes');
        Schema::dropIfExists('customers');

    }
}
