<?php

namespace App\Repositories;

use App\Models\Achievement;
use App\Repositories\Contracts\AchievementInterface;

class AchievementRepository implements AchievementInterface
{
    // Implement the methods from the interface
    public function all()
    {
        return Achievement::all();
    }

    public function find($id)
    {
        // Logic for finding a single item
    }
}
