<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Rooms that have this amenity.
     */
    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'amenity_room');
    }
}
