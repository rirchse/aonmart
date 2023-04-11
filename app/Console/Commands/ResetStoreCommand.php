<?php

namespace App\Console\Commands;

use App\Models\Store;
use Exception;
use Illuminate\Console\Command;

class ResetStoreCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:store {store_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset An Store';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        try {
            $store = Store::find(
                $this->argument('store_id')
            );

            if (!$store) {
                $this->error('Store not found');
                return 0;
            }

            $this->info('Reset Store: ' . $store->name);

            if (!$this->confirm('Do you wish to continue?')) {
                $this->info('Reset store aborted.');
                return 0;
            }

            $store->resetStore();

            $this->info('Reset store completed.');
            return 1;
        } catch (Exception $e) {
            $this->error($e->getMessage());
            return 0;
        }
    }
}

