<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserAchievement;

class UpdateClaimableAttribute extends Command
{
    protected $signature = 'update:claimable';
    protected $description = 'Update claimable attribute for all user achievements';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $achievements = UserAchievement::all();

        foreach ($achievements as $achievement) {
            $achievement->claimable = $achievement->progress >= $achievement->achievement->required_points
                && !$achievement->claimed_at;
            $achievement->save();
        }

        $this->info('Claimable attribute updated for all user achievements.');
    }
}
