<?php

namespace App\Repositories\Contracts;

interface UserInterface
{
    public function all();
    public function find($id);
    public function register(array $data);
    public function login(array $data);
    public function update($id, array $data);
    public function delete($id);
}
