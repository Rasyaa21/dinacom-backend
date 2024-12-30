<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrashCategory extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function trashes()
    {
        return $this->hasMany(Trash::class);
    }

    public function locations()
    {
        return $this->hasMany(TrashLocation::class);
    }
}
