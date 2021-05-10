<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Thu, 25 Mar 2021 15:12:32 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;

class SynchronizeStores extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:stores {storeID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize stores/products';

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


        if ($this->argument('storeID')=='all') {
            $stores = Store::all();
        } else {

            $stores = Store::where('id', $this->argument('storeID'))->get();
        }

        foreach ($stores as $store) {

            print $store->slug."\n";
            $store->storeEngine->setDatabase();
            $store->storeEngine->synchronizeStore($store->foreign_id);
            $store->synchronizeProducts($this->output);
            $store->synchronizeCollections($this->output);

        }


        return 0;
    }
}
