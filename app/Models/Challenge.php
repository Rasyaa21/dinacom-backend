<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challenge extends Model
{
    protected $fillable = [
        'challenge_name',
        'description',
        'required_points',
        'reward_points',
    ];

    public function userChallenges()
    {
        return $this->hasMany(UserChallenge::class);
    }
}

