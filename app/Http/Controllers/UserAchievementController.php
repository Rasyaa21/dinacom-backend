<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserAchievementResource;
use App\Http\Response\ApiResponse;
use App\Repositories\UserAcievementRepository;
use Exception;
use Illuminate\Http\Request;

class UserAchievementController extends Controller
    {
        private UserAcievementRepository $AchievementRepository;

        public function __construct(UserAcievementRepository $AchievementRepository)
        {
            $this->AchievementRepository = $AchievementRepository;
        }

        public function index()
        {
            try {
                $achievements = $this->AchievementRepository->all();
                return new ApiResponse(200, UserAchievementResource::collection($achievements), 'success');
            } catch (Exception $e) {
                return new ApiResponse(500, [$e->getMessage()], 'server error');
            }
        }

        public function show($id)
        {
            try {
                $achievement = $this->AchievementRepository->find($id);
                return new ApiResponse(200, new UserAchievementResource($achievement), 'success');
            } catch (Exception $e) {
                return new ApiResponse(500, [$e->getMessage()], 'server error');
            }
        }

        public function claim($id){
            try{
                $achievement = $this->AchievementRepository->claimAchivement($id);
                return new ApiResponse(200, new UserAchievementResource($achievement), 'success');
            } catch (Exception $e){
                return new ApiResponse(500, [$e->getMessage()], 'server error');
            }
        }
    }
