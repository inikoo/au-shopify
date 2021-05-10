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
            $table->string('slug')->unique();
            $table->foreignId('store_id')->constrained();
            $table->unsignedMediumInteger('foreign_id')->index();
            $table->string('name')->collation('case_insensitive')->index();
            $table->decimal('balance',16)->index();


            $table->unsignedSmallInteger('number_users')->default(0);
            $table->unsignedSmallInteger('number_portfolio_products')->default(0);

            $table->json('data');
            $table->timestampsTz();
            $table->softDeletesTz();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('customer_id')->after('id')->nullable()->constrained();
        });



        Schema::create('access_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->string('access_code')->unique()->index();
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('customer_id');
        });
        Schema::dropIfExists('customers');

    }
}
