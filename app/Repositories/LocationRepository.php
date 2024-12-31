<?php

namespace App\Repositories;

use App\Models\TrashLocation;
use App\Repositories\Contracts\LocationInterface;

class LocationRepository implements LocationInterface
{
    public function all(){
        return TrashLocation::all();
    }

    public function getLocationByTrashCategory($category_id){
        return TrashLocation::where('category_id', $category_id)->get();
    }
}
