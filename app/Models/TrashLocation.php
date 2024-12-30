<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrashLocation extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'trash_category_id'
    ];

    public function category()
    {
        return $this->belongsTo(TrashCategory::class, 'trash_category_id');
    }
}
