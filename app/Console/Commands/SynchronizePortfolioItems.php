<?php /** @noinspection PhpUnused */

/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Mon, 29 Mar 2021 16:38:56 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;


class SynchronizePortfolioItems extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:portfolio_items {customerID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize portfolio items';

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


        if ($this->argument('customerID') == 'all') {
            $customers = Customer::all();
        } else {

            $customers = Customer::where('id', $this->argument('customerID'))->get();
        }

        foreach ($customers as $customer) {

            print $customer->slug."\n";
            $customer->store->storeEngine->setDatabase();
            $customer->store->storeEngine->engine->synchronizeCustomer($customer->store, $customer->foreign_id);
            $customer->synchronizePortfolioItems();
            $customer->updateNumberPortfolioProducts();
            foreach ($customer->users as $user) {
                $user->updatePortfolioStats();
            }

        }


        return 0;
    }
}
