<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardCode extends Model
{
    protected $fillable = [
        'reward_id',
        'code'
    ];


    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    protected $proxies = '*';
}
