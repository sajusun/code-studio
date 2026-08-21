<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMedia;

/**
 * Room model – now supports media (primary image + up to 4 gallery images)
 */
class Room extends Model
{
    use HasMedia;

    protected $fillable = [
        'room_type_id',
        'number',
        'name',
        'description',
        'floor',
        'status',
        'is_visible',
        'notes',
    ];

    /* ---------------- Relationships ---------------- */

    /**
     * The room type this room belongs to.
     */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /**
     * Amenities attached to the room.
     */
    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room');
    }

    /**
     * Tags attached to the room.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'room_tag');
    }

    /* ---------------- Media collections ---------------- */

    /**
     * Primary image (single file, collection "primary").
     */
    public function primaryMedia()
    {
        return $this->mediaCollection('primary')->first();
    }

    /**
     * Gallery images (up to 4, collection "gallery").
     */
    public function galleryMedia()
    {
        return $this->mediaCollection('gallery')->get();
    }
}
