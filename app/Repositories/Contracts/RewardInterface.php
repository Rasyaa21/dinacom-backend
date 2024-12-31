<?php

namespace App\Repositories\Contracts;

interface RewardInterface
{
    public function all();
    public function redeemCode($id);
    public function rewardDetail($id);
}
