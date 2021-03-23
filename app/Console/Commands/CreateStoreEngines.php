<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 16 Mar 2021 15:18:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Console\Commands;

use App\Models\Store;
use Illuminate\Console\Command;

class CreateStoreEngines extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:store_engines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the store engines';

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
        $store       = new Store;
        $store->foreign_store_id = $this->argument('foreign_store_id');
        $store->name = $this->argument('name');
        $store->url  = $this->argument('url');

        $store->data = [
            'subdomain' => $this->argument('subdomain'),
            'code'      => $this->argument('code'),
            'database'  => $this->argument('database'),
        ];

        $store->save();

        print $store->name."\t".$store->createAccessCode()."\n";


        return 0;
    }
}
