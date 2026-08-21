<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Sea View', 'description' => 'Rooms with a view of the sea'],
            ['name' => 'Family Friendly', 'description' => 'Suitable for families'],
            ['name' => 'Business', 'description' => 'Business‑oriented amenities'],
            ['name' => 'Pet Friendly', 'description' => 'Pets allowed'],
            ['name' => 'Luxury', 'description' => 'High‑end luxury rooms'],
        ];

        foreach ($tags as $data) {
            Tag::create($data);
        }
    }
}
