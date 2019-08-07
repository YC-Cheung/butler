<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'butler:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

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
        $email = $this->ask('Please enter a email to login');
        $password = bcrypt($this->secret('Please enter a password to login'));
        $name = $this->ask('Please enter a name to display');

        $user = new User(compact('email', 'password', 'name'));
        $user->save();

        $this->info("User [$email] created successfully.");
    }
}
