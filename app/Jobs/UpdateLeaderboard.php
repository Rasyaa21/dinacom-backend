<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
Use App\Models\User;

class UpdateLeaderboard implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {}

    public function handle(): void
    {
        $users = User::all();
        foreach($users as $user){
            $rank = $user->calculateLeaderboard();
            $user->rank = $rank;
            $user->save();
        }
    }
}
