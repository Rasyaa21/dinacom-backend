<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Schedule;

class GenerateDailyChallenges extends Command
{
    protected $signature = 'challenges:generate-daily';
    protected $description = 'Generate and reset daily challenges for users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->userChallenges()->delete();
            $user->generateChallenge();
        }

        $this->info('Daily challenges have been reset and generated.');
    }

    public function schedule(Schedule $schedule){
        $schedule->command(static::class)->daily();
    }
}
