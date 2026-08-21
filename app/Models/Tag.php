<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Rooms that have this tag.
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_tag');
    }
}
