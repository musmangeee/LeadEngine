<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Faker\Generator as Faker;
use Illuminate\Support\Str;

class generateRandomLead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lead:generateRandomLead';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating random lead';

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
     * @return mixed
     */
    public function handle()
    {
        
    }
}
