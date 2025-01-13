<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserChallenge;
use App\Repositories\Contracts\UserChallengeInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserChallengeRepository implements UserChallengeInterface
{
    public function all()
    {
        $userId = Auth::user()->id;
        return UserChallenge::with('challenge')->where('user_id', $userId)->get();
    }

    public function find($id)
    {
        $userId = Auth::user()->id;
        Log::info("id" . $id);
        return UserChallenge::where('challenge_id', $id)->where('user_id', $userId)->first();
    }

    public function claim($id)
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('User not authenticated');
        }
        if (!($user instanceof User)) {
            throw new \Exception('Authenticated user is not a valid User instance');
        }

        $userChallenge = UserChallenge::where('challenge_id', $id)
            ->where('user_id', $user->id)
            ->where('claimable', true)
            ->with('challenge')
            ->first();

        if (!$userChallenge) {
            throw new \Exception('Challenge not found or not claimable');
        }

        $userChallenge->claimed_at = now();
        $userChallenge->status = 'claimed';
        $userChallenge->claimable = false;

        try {
            $userChallenge->save();
        } catch (\Exception $e) {
            Log::error('Failed to save user challenge: ' . $e->getMessage());
            throw new \Exception('Failed to save user challenge');
        }

        try {
            $user->updatePoints($userChallenge->challenge->reward_points);
        } catch (\Exception $e) {
            Log::error('Failed to update user points: ' . $e->getMessage());
            throw new \Exception('Failed to update user points');
        }

        return $userChallenge;
    }
}
