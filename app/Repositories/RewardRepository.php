<?php

namespace App\Repositories;

use App\Http\Response\ApiResponse;
use App\Repositories\Contracts\RewardInterface;
use App\Models\Reward;
use App\Models\RewardHistory;
use App\Models\Users;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Throw_;

class RewardRepository implements RewardInterface
{
    public function all()
    {
        return Reward::where('stock', '>', 0)->get();
    }

    public function redeemCode($id){
        $user = Auth::user();
        $code = Reward::where('id', $id)->first();
        if (!$code){
            throw new Exception('Voucher Gaada');
        }
        if ($code->stock < 1){
            throw new Exception('Voucher Abis');

        }
        if ($user->points < $code->points_required){
            throw new Exception('Poinmu Gacukup');
        }
        $rewardCode = $code->codes->first();
        $codeNotDeleted = $rewardCode->code;
        RewardHistory::create([
            'user_id' => $user->id,
            'reward_id' => $code->id,
            'redeem_at' => now()->format('Y-m-d H:i:s'),
            'code' => $codeNotDeleted
        ]);
        $rewardCode->delete();
        $code->decrement('stock');
        DB::table('users')->where('id', $user->id)->decrement('points', $code->points_required);
        return $codeNotDeleted;
    }

    public function rewardDetail($id){
        $code = Reward::where('id', $id)->first();
        return $code;
    }

    public function getAllRewardByUserId()
    {
        $userId = Auth::user()->id;
        $rewardsHistories = RewardHistory::where('user_id', $userId)->get();
        return $rewardsHistories;
    }

    public function getRewardDetail($id)
    {
        $userId = Auth::user()->id;
        $rewardHistory = RewardHistory::where('id' , $id)->where('user_id', $userId)->first();
        return $rewardHistory;
    }
}
