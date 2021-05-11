<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        Relation::morphMap(
            [
                'Aurora'     => 'App\Models\Engines\Aurora',
                'Product'    => 'App\Models\Product',
                'Store'      => 'App\Models\Store',
                'Collection' => 'App\Models\Collection',

            ]
        );
    }
}
