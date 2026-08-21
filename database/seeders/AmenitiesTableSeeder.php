<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Amenity;

class AmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $amenities = [
            ['name' => 'WiFi', 'description' => 'High speed wireless internet'],
            ['name' => 'Air Conditioning', 'description' => 'Cooling system for comfort'],
            ['name' => 'Mini Bar', 'description' => 'Snacks and drinks in-room'],
            ['name' => 'Television', 'description' => 'Flat‑screen TV with cable channels'],
            ['name' => 'Balcony', 'description' => 'Private outdoor space'],
        ];

        foreach ($amenities as $data) {
            Amenity::create($data);
        }
    }
}
