<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserInterface;
use Illuminate\Http\Request;

class RegisterController extends Controller
{

    private UserInterface $userRepository;

    public function __construct(UserInterface $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register(){

    }
}
