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
use Illuminate\Support\Facades\Storage;
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

    public function leaderboard(){
        try{
            $users = $this->userRepository->leaderboard();
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

    public function updateProfile(Request $req)
    {
        try {
            Log::info($req->all());
            $user = Auth::user();
            if (!$user) {
                return new ApiResponse(401, [], 'User not authenticated');
            }
            $data = $req->except('profile_image');
            $path = null;
            if ($req->hasFile('profile_image')) {
                $path = $req->file('profile_image')->store('profile_images', 'public');
                $data['profile_image'] = $path;
            }
            $this->userRepository->update($user->id, $data);
            return new ApiResponse(201, ['message' => 'Profile updated successfully']);
        } catch (Exception $e) {
            return new ApiResponse(500, [$e->getMessage()], 'Error while updating profile');
        }
    }

    public function updateImage(Request $req){
        try{
            $user = Auth::user();
            $image = $req->file('trash_image');
            if(!$image){
                Log::info('image gaada');
            }
            Log::info($image);
            // $this->userRepository->updateImage($user->id, $req->);
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

    public function getUserByName(){
        try{
            $user = $this->userRepository->getUserByName();
            return new ApiResponse(200, ["data" => new UserResource($user)], 'success retreive user data.');
        } catch (Exception $e){
            return new ApiResponse(500, [$e->getMessage()], 'Error logging out: ' . $e->getMessage());
        }
    }
}
