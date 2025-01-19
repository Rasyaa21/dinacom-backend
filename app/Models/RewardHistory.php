<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardHistory extends Model
{
    protected $fillable = [
        'user_id',
        'reward_id',
        'redeem_at',
        'code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }
}
