<?php

namespace App\Repositories;

use App\Http\Response\ApiResponse;
use App\Repositories\Contracts\RewardInterface;
use App\Models\Reward;
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
        $rewardCode->delete();
        $code->decrement('stock');
        DB::table('users')->where('id', $user->id)->decrement('points', $code->points_required);
        return $codeNotDeleted;
    }

    public function rewardDetail($id){
        $code = Reward::where('id', $id)->first();
        return $code;
    }
}
