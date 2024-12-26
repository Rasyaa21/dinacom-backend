<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserInterface;
use App\Response\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private UserInterface $userRepository;
    public function __construct(UserInterface $userRepository) {
        $this->userRepository = $userRepository;
    }


    public function login(LoginRequest $req){
        try{
            $data = $req->validated();
            $user = $this->userRepository->login($data);
            $token = $user->createToken('token')->plainTextToken;
            return new ApiResponse(201, [new UserResource($user), 'token' => $token]);
        } catch (Exception $e){
            return new ApiResponse(500, [], 'server error', $e->getMessage());
        }
    }

    public function getAllData(){
        try{
            $users = $this->userRepository->all();
            return new ApiResponse(200, [UserResource::collection($users)]);
        } catch (Exception $e){
            return new ApiResponse(500, [], 'error while fetching all the user data', $e->getMessage());
        }
    }

    public function findUser($uuid){
        try{
            $user = User::where('uuid', $uuid);
            return new ApiResponse(200, [new UserResource($user)]);
        } catch (Exception $e){
            return new ApiResponse(500, [], 'error while finding user', $e->getMessage());
        }
    }

    public function updateProfile(UpdateUserRequest $req){
        try{
            $data = $req->validated();
            $userId = Auth::id();
            $this->userRepository->updateProfile($userId, $data);
        } catch (Exception $e){
            return new ApiResponse(500, [], 'error while updatig user profile', $e->getMessage());
        }
    }

    public function logout(Request $req){
        try{
            $req->user()->tokens()->each(function ($token){
                $token->delete();
            });
            new ApiResponse(200, [], 'successfully logged out.');
        } catch (Exception $e){
            return new ApiResponse(500, [], 'Error logging out: ' . $e->getMessage());
        }
    }
}
