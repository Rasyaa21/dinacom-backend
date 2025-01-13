<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAchievement extends Model
{
    protected $fillable = [
        'user_id',
        'achievement_id',
        'status',
        'progress',
        'claimed_at',
        'claimable'
    ];

    protected $casts = [
        'claimable' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function achievement()
    {
        return $this->belongsTo(Achievement::class);
    }

    public function getClaimableAttribute()
    {
        return $this->progress >= $this->achievement->required_points && !$this->claimed_at;
    }

    protected static function booted()
    {
        static::saving(function ($achievement) {
            $achievement->claimable = $achievement->progress >= $achievement->achievement->required_points
                && !$achievement->claimed_at;

            if($achievement->claimable){
                $achievement->status = 'completed';
            }
        });
    }
}
