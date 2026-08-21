<?php

namespace Database\Seeders;

use App\Modules\Guest\Models\Guest;
use App\Models\Room;
use App\Modules\Room\Models\RoomType;
use App\Modules\Shared\Enums\RoomStatus;
use App\Models\Amenity;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class HmsSeeder extends Seeder
{
    public function run(): void
    {
        $standard = RoomType::query()->create([
            'name' => 'Standard',
            'description' => 'Comfortable room with queen bed',
            'base_rate' => 89.00,
            'capacity' => 2,
        ]);

        $deluxe = RoomType::query()->create([
            'name' => 'Deluxe',
            'description' => 'Spacious room with king bed and city view',
            'base_rate' => 129.00,
            'capacity' => 2,
        ]);

        $suite = RoomType::query()->create([
            'name' => 'Suite',
            'description' => 'Premium suite with living area',
            'base_rate' => 199.00,
            'capacity' => 4,
        ]);

        foreach ([$standard, $deluxe, $suite] as $index => $type) {
            for ($i = 1; $i <= 5; $i++) {
                $room = Room::query()->create([
                    'room_type_id' => $type->id,
                    'description' => $type->description,
                    'number' => strtoupper(substr($type->name, 0, 1)) . ($index * 100 + $i),
                    'floor' => $index + 1,
                    'status' => RoomStatus::Available,
                    'is_visible' => true,
                    'notes' => 'Room ' . $i . ' of type ' . $type->name,
                ]);
                // Attach random amenities (2) and tags (2) to the room
                $amenityIds = Amenity::inRandomOrder()->limit(2)->pluck('id');
                $tagIds = Tag::inRandomOrder()->limit(2)->pluck('id');
                $room->amenities()->attach($amenityIds);
                $room->tags()->attach($tagIds);
            }
        }

        Guest::query()->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '+1-555-0100',
            'document_number' => 'P12345678',
        ]);

        Guest::query()->create([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'email' => 'john.smith@example.com',
            'phone' => '+1-555-0101',
        ]);
    }
}
