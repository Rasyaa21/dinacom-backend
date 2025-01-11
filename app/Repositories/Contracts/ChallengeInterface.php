<?php

namespace App\Repositories\Contracts;

interface ChallengeInterface
{
    // Define the methods that the repository will implement
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}