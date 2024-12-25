<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{
    protected $fillable = [
        'trash_image',
        'trash_name',
        'description',
        'user_id',
        'trash_category_id',
    ];

    /**
     * Get the user that owns the Trash
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category that owns the Trash
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(TrashCategory::class);
    }
}
