<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'uuid' => 'string'
        ];
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

    public static function booted()
    {
        static::creating(function($user) {
            $user->uuid = Str::uuid()->toString();
            $user->exp = 1;
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
}
