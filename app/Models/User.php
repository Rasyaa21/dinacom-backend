<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use App\Repositories\UserRepository;

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
        'uuid'
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

    public function calculateLeaderboard(){
        //if
        $higherRankUsers = User::where('exp' ,'>' ,$this->exp)
            ->orWhere(function ($query){
                //else
                $query->where('exp', '=', $this->exp)
                //incase the users has a same exp
                    ->where('id', '<' , $this->id);
            })
            ->count();
        return $higherRankUsers + 1;
    }

    public static function booted(){
        static::saving(function ($user){
            $user->rank = $user->calculateRank();
        });

        static::saving(function ($user){
            $user->leaderboard = $user->calculateLeaderboard();
        });

        static::creating(function($user){
            $user->uuid = Str::uuid()->toString();
        });
    }
}
