<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
        'reward_name',
        'code',
        'reward_image',
        'description',
        'points_required',
        'stock'
    ];

    /**
     * Get all of the histories for the Reward
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function histories()
    {
        return $this->hasMany(RewardHistory::class);
    }
}
