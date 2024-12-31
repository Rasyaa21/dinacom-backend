<?php

namespace App\Http\Controllers;

use App\Http\Resources\RewardResResource;
use App\Http\Response\ApiResponse;
use App\Repositories\Contracts\RewardInterface;
use Exception;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    private RewardInterface $rewardRepository;
    public function __construct(
        RewardInterface $rewardRepository
    ) {
        $this->rewardRepository = $rewardRepository;
    }

    public function getAllAvailVoucher(){
        try{
            $vouchers = $this->rewardRepository->all();
            return new ApiResponse(200, RewardResResource::collection($vouchers), 'data voucher berhasil di dapatkan');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function redeemCode($id){
        try {
            $code = $this->rewardRepository->redeemCode($id);
            return new ApiResponse(200, ['voucher' => $code], 'berhasil menukar voucer');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function rewardDetail($id){
        try {
            $code = $this->rewardRepository->rewardDetail($id);
            return new ApiResponse(200, new RewardResResource($code), 'data voucher berhasil didapatkan');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

}
