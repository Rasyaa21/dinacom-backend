<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Filament\Panel;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'points',
        'level',
        'rank',
        'uuid',
        'exp',
        'leaderboard'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'uuid' => 'string'
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function userAchievements()
    {
        return $this->hasMany(UserAchievement::class);
    }

    public function userChallenges(){
        return $this->hasMany(UserChallenge::class);
    }

    public function calculateRank(){
        if($this->exp < 5000){
            return 'Bronze';
        } elseif ($this->exp < 10000){
            return 'Silver';
        } elseif ($this->exp < 15000){
            return 'Gold';
        } elseif ($this->exp < 20000){
            return 'Platinum';
        } else {
            return 'Diamond';
        }
    }

    public function calculateLeaderboard()
    {
        $users = self::orderBy('exp', 'desc')->get();
        foreach ($users as $index => $user) {
            $user->withoutEvents(function () use ($user, $index) {
                $user->update(['leaderboard' => $index + 1]);
            });
        }
    }

    public function generateAchievement()
    {
        $achievements = Achievement::all();
        foreach ($achievements as $achievement){
            $this->userAchievements()->create([
                'achievement_id' => $achievement->id,
                'user_id' => $this->id,
                'status' => 'in_progress',
                'progress' => 0,
            ]);
        }
    }

    public function generateChallenge(){
        $challenges = Challenge::inRandomOrder()->take(3)->get();
        foreach ($challenges as $challenge){
            $this->userChallenges()->create([
                'challenge_id' => $challenge->id,
                'user_id' => $this->id,
                'status' => 'in_progress',
                'progress' => 0
            ]);
        }
    }

    public function rewardHistories(){
        return $this->hasMany(RewardHistory::class);
    }


    public static function booted()
    {
        static::creating(function($user) {
            $user->uuid = Str::uuid()->toString();
            $user->exp = 1;
        });

        static::created(function($user){
            $user->generateAchievement();
        });

        static::saving(function ($user) {
            $user->rank = $user->calculateRank();
        });

        static::updated(function ($user) {
            if ($user->wasChanged('exp')) {
                dispatch(function () {
                    (new self())->calculateLeaderboard();
                })->afterResponse();
            }
        });
    }

    public function updatePoints($points)
    {
        $this->points += $points;
        $this->save();
    }
}
