<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Tymon\JWTAuth\Facades\JWTAuth;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'butler:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate JWT token for authorization';

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
        $userType = $this->choice('Please choose the account type', ['admin', 'user']);
        $userId = $this->ask('Please enter a user id');
        $days = $this->ask('Please enter a expired days');

        $userModel = $userType === 'admin' ? 'App\Models\Administrator' : 'App\Models\User';

        $user = $userModel::find($userId);
        if (!$user) {
            $this->warn('The user not found');
            return;
        }

        $ttl = (int)$days * 24 * 60;
        JWTAuth::factory()->setTTL($ttl);
        $token = JWTAuth::fromUser($user);

        $this->info($token);
    }
}
