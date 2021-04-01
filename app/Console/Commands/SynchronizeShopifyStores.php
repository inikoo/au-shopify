<?php

/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 30 Mar 2021 14:28:02 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;


class SynchronizeShopifyStores extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:shopify {userID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Shopify store';

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
     * @throws \Exception
     */
    public function handle(): int {


        if (strtolower($this->argument('userID')) == 'all') {
            $users = User::all();
        } else {

            $users = User::where('id', $this->argument('userID'))->get();
        }

        foreach ($users as $user) {
            $user->updateStats();


            print $user->name."\n";
            $user->synchronizeStore();
            $user->synchronizeProducts();
            $user->synchronizePortfolio();

            $user->createWebhooks();
            $user->updateStats();




        }


        return 0;
    }
}
