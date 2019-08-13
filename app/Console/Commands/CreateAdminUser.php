<?php

namespace App\Console\Commands;

use App\Models\Administrator;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user';

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
        $username = $this->ask('Please enter a username to login');
        $password = bcrypt($this->secret('Please enter a password to login'));

        $user = new Administrator(compact('username', 'password'));
        $user->save();

        $this->info("User [$username] created successfully.");
    }
}
