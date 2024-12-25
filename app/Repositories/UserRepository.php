<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::where('id', $id);
    }

    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function login(array $data)
    {
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])){
            return false;
        };
        return Auth::user();
    }

    public function updateProfile($id, array $data)
    {
        $user = User::find($id);
        return $user->update([
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'profile_image' => $data['profile_image']
        ]);
    }

    public function delete($id)
    {
        return User::find($id)->delete();
    }
}
