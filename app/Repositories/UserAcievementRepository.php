<?php

namespace App\Repositories;

use App\Models\UserAchievement;
use App\Repositories\Contracts\UserAcievementInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;


class UserAcievementRepository implements UserAcievementInterface
{
    public function all()
    {
        $userId = Auth::user()->id;
        return UserAchievement::with('achievement')->where('user_id', $userId)->get();
    }

    public function find($id)
    {
        $userId = Auth::user()->id;
        return UserAchievement::find($id)->where('user_id', $userId)->first();
    }

    public function claimAchivement($id)
    {
        $user = Auth::user();
        if (!$user) {
            throw new \Exception('User not authenticated');
        }

        if (!($user instanceof User)) {
            throw new \Exception('Authenticated user is not a valid User instance');
        }

        $userAchievement = UserAchievement::where('achievement_id', $id)
            ->where('user_id', $user->id)
            ->where('claimable', true)
            ->with('achievement')
            ->first();

        if (!$userAchievement) {
            throw new \Exception('Achievement not found or not claimable');
        }

        $userAchievement->claimed_at = now();
        $userAchievement->claimable = false;

        try {
            $userAchievement->save();
        } catch (\Exception $e) {
            Log::error('Failed to save user achievement: ' . $e->getMessage());
            throw new \Exception('Failed to save user achievement');
        }

        try {
            $user->updatePoints($userAchievement->achievement->reward_points);
        } catch (\Exception $e) {
            Log::error('Failed to update user points: ' . $e->getMessage());
            throw new \Exception('Failed to update user points');
        }

        return $userAchievement;
    }
}
