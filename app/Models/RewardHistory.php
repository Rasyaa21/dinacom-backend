<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardHistory extends Model
{
    protected $fillable = [
        'user_id',
        'reward_id',
        'redeem_at'
    ];

    /**
     * Get the user that owns the RewardHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the reward that owns the RewardHistory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
