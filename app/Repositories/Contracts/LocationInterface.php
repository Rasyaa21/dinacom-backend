<?php

namespace App\Repositories\Contracts;

interface LocationInterface
{
    public function all();
    public function getLocationByTrashCategory($category_id);
}
