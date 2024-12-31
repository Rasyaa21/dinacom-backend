<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'reward_name',
        'reward_image',
        'description',
        'points_required',
        'stock'
    ];

    public function histories()
    {
        return $this->hasMany(RewardHistory::class);
    }

    public function codes(){
        return $this->hasMany(RewardCode::class);
    }
}
