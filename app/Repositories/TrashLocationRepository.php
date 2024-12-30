<?php

namespace App\Repositories;

use App\Models\TrashLocation;
use App\Repositories\Contracts\TrashLocationInterface;

class TrashLocationRepository implements TrashLocationInterface
{
    public function all()
    {
        return TrashLocation::all();
    }

    public function find($id){
        return TrashLocation::where('id', $id)->first();
    }

    public function getLocationByCategory($id){
        return TrashLocation::where('trash_category_id', $id)->get();
    }
}
