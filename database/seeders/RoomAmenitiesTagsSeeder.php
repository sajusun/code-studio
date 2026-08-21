<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Amenity;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class RoomAmenitiesTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenityIds = Amenity::pluck('id')->toArray();
        $tagIds = Tag::pluck('id')->toArray();

        // Attach a random subset of amenities and tags to each room.
        Room::all()->each(function (Room $room) use ($amenityIds, $tagIds) {
            // Pick 2‑3 random amenities
            $selectedAmenities = array_rand(array_flip($amenityIds), rand(2, min(3, count($amenityIds)));
            $room->amenities()->attach($selectedAmenities);

            // Pick 1‑2 random tags
            $selectedTags = array_rand(array_flip($tagIds), rand(1, min(2, count($tagIds))));
            $room->tags()->attach($selectedTags);
        });
    }
}
