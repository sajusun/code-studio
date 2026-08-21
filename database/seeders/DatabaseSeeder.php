<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed 10 random users
        User::factory(10)->create();

        // Seed roles and permissions
        $this->call([
            RolesAndPermissionsSeeder::class,
            HmsSeeder::class,
            TagsTableSeeder::class,
            AmenitiesTableSeeder::class,
        ]);

        // Optionally, create an admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@hms.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
    }
}
