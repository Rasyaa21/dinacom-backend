<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Log;
use App\Http\Response\ApiResponse;
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
            return new ApiResponse(201, ['user' => new UserResource($user), 'token' => $token]);
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'server error');
        }
    }

    public function getAllData(){
        try{
            $users = $this->userRepository->all();
            return new ApiResponse(200, [UserResource::collection($users)]);
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'error while fetching all the user data');
        }
    }

    public function findUser($uuid){
        try{
            $user = User::where('uuid', $uuid)->first();
            return new ApiResponse(200, [new UserResource($user)]);
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'error while finding user');
        }
    }

    public function updateProfile(UpdateUserRequest $req)
    {
        try {
            $data = $req->validated();
            $user = Auth::user();
            Log::info('Authenticated User:', ['user' => $user]);
            if (!$user) {
                return new ApiResponse(401, [], 'User not authenticated');
            }
            $this->userRepository->update($user->id, $data);
            return new ApiResponse(201, ['message' => 'Profile updated successfully']);
        } catch (Exception $e) {
            return new ApiResponse(500, [$e->getMessage()], 'Error while updating profile');
        }
    }


    public function logout(Request $req){
        try{
            $req->user()->tokens()->each(function ($token){
                $token->delete();
            });
            new ApiResponse(200, [], 'successfully logged out.');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'Error logging out: ' . $e->getMessage());
        }
    }
}
