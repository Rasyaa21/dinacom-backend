<?php

namespace App\Repositories\Contracts;

interface AchievementInterface
{
    // Define the methods that the repository will implement
    public function all();
    public function find($id);
}
