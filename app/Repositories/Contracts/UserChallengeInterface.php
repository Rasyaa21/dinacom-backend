<?php

namespace App\Repositories\Contracts;

interface UserChallengeInterface
{
    // Define the methods that the repository will implement
    public function all();
    public function find($id);
    public function claim($id);
}
