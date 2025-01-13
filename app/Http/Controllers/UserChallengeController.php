<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserChallenge;
use App\Http\Response\ApiResponse;
use App\Repositories\UserChallengeRepository;
use Exception;
use Illuminate\Http\Request;

class UserChallengeController extends Controller
{
    private UserChallengeRepository $challengeRepository;

    public function __construct(UserChallengeRepository $challengeRepository)
    {
        $this->challengeRepository = $challengeRepository;
    }

    public function index(){
        try{
            $challenges = $this->challengeRepository->all();
            return new ApiResponse(200, UserChallenge::collection($challenges), 'success');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function find($id){
        try{
            $challenge = $this->challengeRepository->find($id);
            return new ApiResponse(200, new UserChallenge($challenge), 'success');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function claim($id){
        try{
            $challenge = $this->challengeRepository->claim($id);
            return new ApiResponse(200, new UserChallenge($challenge), 'success');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }
}
