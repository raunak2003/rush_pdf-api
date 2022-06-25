<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DBinfo extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DBinfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows Information about Current Database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Current Database : '.DB::connection()->getDatabaseName());
    }
}
