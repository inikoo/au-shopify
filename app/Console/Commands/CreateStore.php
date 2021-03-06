<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 16 Mar 2021 15:18:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Console\Commands;

use App\Models\StoreEngine;
use Illuminate\Console\Command;

class CreateStore extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:store {store_engine} {foreign_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new store';

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

        $storeEngine = StoreEngine::firstWhere('slug', $this->argument('store_engine'));
        $storeEngine->setDatabase();


        /**
         * @var $store \App\Models\Store
         */
        $store = $storeEngine->synchronizeStore($this->argument('foreign_id'));

        $store->saveStoreEngineToken();

        return 0;
    }
}
