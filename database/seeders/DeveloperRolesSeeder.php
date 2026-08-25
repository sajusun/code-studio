<?php

namespace Database\Seeders;

use App\Models\DeveloperRole;
use Illuminate\Database\Seeder;

class DeveloperRolesSeeder extends Seeder
{
    private array $roles = [
        [
            'name'        => 'Frontend Developer',
            'slug'        => 'frontend-developer',
            'color'       => '#3B82F6',
            'icon'        => 'Globe',
            'description' => 'Builds user interfaces with HTML, CSS, JavaScript, React, Vue, Next.js etc.',
            'sort_order'  => 1,
        ],
        [
            'name'        => 'Backend Developer',
            'slug'        => 'backend-developer',
            'color'       => '#10B981',
            'icon'        => 'Server',
            'description' => 'Builds server-side logic with Laravel, Node.js, Django, Spring Boot etc.',
            'sort_order'  => 2,
        ],
        [
            'name'        => 'Full Stack Developer',
            'slug'        => 'full-stack-developer',
            'color'       => '#F97316',
            'icon'        => 'Layers',
            'description' => 'Handles both frontend and backend development end-to-end.',
            'sort_order'  => 3,
        ],
        [
            'name'        => 'Flutter Developer',
            'slug'        => 'flutter-developer',
            'color'       => '#06B6D4',
            'icon'        => 'Cpu',
            'description' => 'Builds cross-platform mobile apps using Flutter & Dart.',
            'sort_order'  => 4,
        ],
        [
            'name'        => 'Android Developer',
            'slug'        => 'android-developer',
            'color'       => '#22C55E',
            'icon'        => 'Smartphone',
            'description' => 'Builds native Android apps with Java or Kotlin.',
            'sort_order'  => 5,
        ],
        [
            'name'        => 'iOS Developer',
            'slug'        => 'ios-developer',
            'color'       => '#F59E0B',
            'icon'        => 'Apple',
            'description' => 'Builds native iOS apps with Swift or Objective-C.',
            'sort_order'  => 6,
        ],
        [
            'name'        => 'Java Developer',
            'slug'        => 'java-developer',
            'color'       => '#EF4444',
            'icon'        => 'Coffee',
            'description' => 'Enterprise applications and backend services using Java and Spring.',
            'sort_order'  => 7,
        ],
        [
            'name'        => 'Mobile App Developer',
            'slug'        => 'mobile-app-developer',
            'color'       => '#8B5CF6',
            'icon'        => 'Smartphone',
            'description' => 'General mobile app development across Android and iOS platforms.',
            'sort_order'  => 8,
        ],
        [
            'name'        => 'DevOps Engineer',
            'slug'        => 'devops-engineer',
            'color'       => '#6366F1',
            'icon'        => 'Shield',
            'description' => 'CI/CD pipelines, cloud infrastructure, Docker, Kubernetes.',
            'sort_order'  => 9,
        ],
        [
            'name'        => 'UI/UX Designer',
            'slug'        => 'ui-ux-designer',
            'color'       => '#EC4899',
            'icon'        => 'Sparkles',
            'description' => 'User interface design, prototyping, and user experience optimization.',
            'sort_order'  => 10,
        ],
    ];

    public function run(): void
    {
        $this->command->info('Seeding developer roles...');

        foreach ($this->roles as $role) {
            DeveloperRole::firstOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }

        $this->command->info('✓ ' . count($this->roles) . ' developer roles seeded.');
    }
}
