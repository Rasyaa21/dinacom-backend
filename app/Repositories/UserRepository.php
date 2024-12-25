<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserRepository implements UserInterface
{
    // Implement the methods from the interface
    public function all()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::where('id', $id);
    }

    public function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return $user;
    }

    public function update($id, array $data)
    {
        // Logic for updating an item
    }

    public function delete($id)
    {
        // Logic for deleting an item
    }
}
