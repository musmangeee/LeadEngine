<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:createAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin';

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
        $user = User::create([
            'email' => 'admin@aimleadengine.com',
            'first_name' => 'Admin',
            'last_name' => 'AIM',
            'timezone' => 'UTC',
            'password' => bcrypt('admin'),
            'status' => 'active'
        ]);

        $user->assignRole('Admin');
    }
}
