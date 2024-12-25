<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrashCategory extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Get all of the trashes for the TrashCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trashes()
    {
        return $this->hasMany(Trash::class);
    }
}
