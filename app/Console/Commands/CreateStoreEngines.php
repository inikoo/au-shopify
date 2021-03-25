<?php
/*
 * Author: Raul A Perusquía-Flores (raul@aiku.io)
 * Created: Tue, 16 Mar 2021 15:18:24 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2021. Aiku.io
 */

namespace App\Console\Commands;

use App\Models\Engines\Aurora;

use Illuminate\Console\Command;

/**
 * Class CreateStoreEngines
 *
 * @package App\Console\Commands
 */
class CreateStoreEngines extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:store_engine {engine_type} {arg1}';

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


        switch ($this->argument('engine_type')) {
            case 'au':
                $storeEngineType = Aurora::firstOrCreate(
                    [
                        'slug' => 'v3'
                    ]
                );

                $storeEngineType->setDatabase($this->argument('arg1'));

                $storeEngine = $storeEngineType->synchronizeStoreEngine(
                    [
                        'id'       => 1,
                        'database' => $this->argument('arg1'),
                        'code' => $this->argument('arg1')
                    ]
                );

                break;
            default:
                print 'Error '.$this->argument('engine_type').' not set up';
                exit;

        }


        $storeEngine->engine()->associate($storeEngineType)->save();


        return 0;
    }
}
