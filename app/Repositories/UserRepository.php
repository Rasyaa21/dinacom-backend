<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserRepository implements UserInterface
{
    public function leaderboard()
    {
        $users = User::all();
        $sorted = $users->sortByDesc('exp');
        return $sorted;
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

    public function update($id, array $data)
    {
        $user = User::find($id);
        $updateData = [
            'name' => $data['name'] ?? $user->name,
            'password' => isset($data['password']) ? Hash::make($data['password']) : $user->password,
        ];
        if (isset($data['profile_image'])) {
            $updateData['profile_image'] = $data['profile_image'];
        }
        return $user->update($updateData);
    }

    public function delete($id)
    {
        return User::find($id)->delete();
    }

    public function getUserByName(){
        $UserId = Auth::user()->id;
        return User::find($UserId);
    }
}
