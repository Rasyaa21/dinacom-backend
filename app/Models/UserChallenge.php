<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChallenge extends Model
{
    protected $fillable = [
        'user_id',
        'challenge_id',
        'progress',
        'status',
        'claimable',
        'claimed_at'
    ];

    protected $casts = [
        'claimable' => 'boolean'
    ];

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}

