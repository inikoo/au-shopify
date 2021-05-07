<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Fri, 07 May 2021 15:07:30 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;

class CreateStoreApiUser extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:store_api_user {user_name} {store_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new api store external user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int {

        $store = Store::find($this->argument('store_id'));
        $token = $store->createToken($this->argument('user_name'), ['is-shopify_product_app'])->plainTextToken;

        print $token."\n";

        return 0;
    }
}
