<?php

namespace App\Repositories\Contracts;

interface TrashLocationInterface
{
    public function all();
    public function find($id);
    public function getLocationByCategory($id);
}
