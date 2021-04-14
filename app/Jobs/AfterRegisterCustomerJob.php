<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AfterRegisterCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $customer;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customerId)
    {
        $this->customer=Customer::find($customerId);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->customer->store->storeEngine->setDatabase();
        $this->customer->synchronizePortfolioItems();
    }
}
