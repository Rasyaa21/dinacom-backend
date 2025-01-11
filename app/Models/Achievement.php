<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'description',
        'required_points',
        'criteria',
        'reward_points',
        'type'
    ];

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }
}
