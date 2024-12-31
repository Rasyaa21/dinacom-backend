<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;

class UpdateLeaderboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leaderboard:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        foreach($users as $user){
            $rank = $user->calculateLeaderboard();
            $user->rank = $rank;
            $user->save();
        }
    }

    public function schedule(Schedule $schedule): void{
        $schedule->command('leaderboard:update')->everyTwoMinutes();
    }
}
