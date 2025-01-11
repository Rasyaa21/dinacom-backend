<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrashLocation extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'trash_category_id',
        'location_name',
        'description',
        'address',
        'location_image'
    ];

    public function category()
    {
        return $this->belongsTo(TrashCategory::class, 'trash_category_id');
    }

    protected $proxies = '*';
}
