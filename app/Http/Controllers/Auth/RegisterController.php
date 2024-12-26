<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserInterface;
use App\Response\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    private UserInterface $userRepository;

    public function __construct(UserInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $req){
        try{
            $data = $req->validated();
            $user = $this->userRepository->register($data);
            $token = $user->createToken('token')->plainTextToken;
            return new ApiResponse(201, [new UserResource($user), 'token' => $token]);
        } catch (Exception $e){
            return new ApiResponse(500, [], 'server error', $e->getMessage());
        }
    }
}
