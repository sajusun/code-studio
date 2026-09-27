<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\DeveloperRole;
use Illuminate\Database\Seeder;

class DeveloperSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding sample developers...');

        // Load role IDs by slug for clean mapping
        $roles = DeveloperRole::pluck('id', 'slug');

        $developers = [
            [
                'data' => [
                    'name'             => 'Arif Hossain',
                    'slug'             => 'arif-hossain',
                    'email'            => 'arif@devteam.io',
                    'phone'            => '+880 1711-000001',
                    'bio'              => 'Passionate full-stack developer with 5+ years of experience building SaaS platforms using Laravel and Next.js. Loves clean architecture and well-documented APIs.',
                    'github_url'       => 'https://github.com/arifhossain',
                    'linkedin_url'     => 'https://linkedin.com/in/arifhossain',
                    'portfolio_url'    => 'https://arifhossain.dev',
                    'skills'           => ['Laravel', 'Next.js', 'MySQL', 'Redis', 'Docker', 'TailwindCSS'],
                    'experience_years' => 5,
                    'status'           => 'published',
                    'sort_order'       => 1,
                ],
                'roles' => ['full-stack-developer', 'backend-developer'],
            ],
            [
                'data' => [
                    'name'             => 'Sadia Islam',
                    'slug'             => 'sadia-islam',
                    'email'            => 'sadia@devteam.io',
                    'phone'            => '+880 1711-000002',
                    'bio'              => 'Flutter enthusiast building beautiful, performant cross-platform apps for Android and iOS. Has delivered 12+ Flutter projects across fintech and e-commerce.',
                    'github_url'       => 'https://github.com/sadiaislam',
                    'linkedin_url'     => 'https://linkedin.com/in/sadiaislam',
                    'twitter_url'      => 'https://twitter.com/sadia_flutter',
                    'skills'           => ['Flutter', 'Dart', 'Firebase', 'REST APIs', 'GetX', 'BLoC'],
                    'experience_years' => 4,
                    'status'           => 'published',
                    'sort_order'       => 2,
                ],
                'roles' => ['flutter-developer', 'mobile-app-developer'],
            ],
            [
                'data' => [
                    'name'             => 'Rakibul Islam',
                    'slug'             => 'rakibul-islam',
                    'email'            => 'rakib@devteam.io',
                    'phone'            => '+880 1711-000003',
                    'bio'              => 'Senior Android developer specializing in native Kotlin apps. Expert in MVVM, Jetpack Compose, and integrating complex third-party SDKs. 7 years in mobile development.',
                    'github_url'       => 'https://github.com/rakibulislam',
                    'linkedin_url'     => 'https://linkedin.com/in/rakibulislam',
                    'skills'           => ['Kotlin', 'Java', 'Android SDK', 'Jetpack Compose', 'MVVM', 'Room DB', 'Retrofit'],
                    'experience_years' => 7,
                    'status'           => 'published',
                    'sort_order'       => 3,
                ],
                'roles' => ['android-developer', 'java-developer'],
            ],
            [
                'data' => [
                    'name'             => 'Nusrat Jahan',
                    'slug'             => 'nusrat-jahan',
                    'email'            => 'nusrat@devteam.io',
                    'phone'            => '+880 1711-000004',
                    'bio'              => 'Creative UI/UX designer who transforms ideas into pixel-perfect, user-friendly interfaces. Specializes in design systems, Figma prototyping, and design handoff.',
                    'linkedin_url'     => 'https://linkedin.com/in/nusratjahan',
                    'portfolio_url'    => 'https://nusrat.design',
                    'twitter_url'      => 'https://twitter.com/nusrat_ux',
                    'skills'           => ['Figma', 'Adobe XD', 'Sketch', 'User Research', 'Prototyping', 'Design Systems', 'Illustration'],
                    'experience_years' => 4,
                    'status'           => 'published',
                    'sort_order'       => 4,
                ],
                'roles' => ['ui-ux-designer'],
            ],
            [
                'data' => [
                    'name'             => 'Tanvir Ahmed',
                    'slug'             => 'tanvir-ahmed',
                    'email'            => 'tanvir@devteam.io',
                    'phone'            => '+880 1711-000005',
                    'bio'              => 'Backend engineer with deep expertise in RESTful APIs, microservices, and cloud infrastructure. Loves optimizing database queries and building scalable server architectures.',
                    'github_url'       => 'https://github.com/tanvirahmed',
                    'linkedin_url'     => 'https://linkedin.com/in/tanvirahmed',
                    'skills'           => ['PHP', 'Laravel', 'Node.js', 'PostgreSQL', 'MongoDB', 'AWS', 'Microservices'],
                    'experience_years' => 6,
                    'status'           => 'published',
                    'sort_order'       => 5,
                ],
                'roles' => ['backend-developer'],
            ],
            [
                'data' => [
                    'name'             => 'Mahfuz Rahman',
                    'slug'             => 'mahfuz-rahman',
                    'email'            => 'mahfuz@devteam.io',
                    'phone'            => '+880 1711-000006',
                    'bio'              => 'Frontend specialist building responsive, accessible web apps with React and Vue.js. Passionate about web performance, animations, and component-driven development.',
                    'github_url'       => 'https://github.com/mahfuzrahman',
                    'linkedin_url'     => 'https://linkedin.com/in/mahfuzrahman',
                    'portfolio_url'    => 'https://mahfuz.dev',
                    'skills'           => ['React', 'Vue.js', 'TypeScript', 'TailwindCSS', 'Next.js', 'GSAP', 'Webpack'],
                    'experience_years' => 3,
                    'status'           => 'published',
                    'sort_order'       => 6,
                ],
                'roles' => ['frontend-developer'],
            ],
            [
                'data' => [
                    'name'             => 'Farhan Haque',
                    'slug'             => 'farhan-haque',
                    'email'            => 'farhan@devteam.io',
                    'phone'            => '+880 1711-000007',
                    'bio'              => 'DevOps engineer managing CI/CD pipelines, container orchestration, and cloud deployments. Keeps production healthy, fast, and monitored 24/7.',
                    'github_url'       => 'https://github.com/farhanhaque',
                    'linkedin_url'     => 'https://linkedin.com/in/farhanhaque',
                    'skills'           => ['Docker', 'Kubernetes', 'CI/CD', 'GitHub Actions', 'AWS EC2', 'Nginx', 'Terraform', 'Linux'],
                    'experience_years' => 5,
                    'status'           => 'published',
                    'sort_order'       => 7,
                ],
                'roles' => ['devops-engineer'],
            ],
            [
                'data' => [
                    'name'             => 'Sumaiya Akter',
                    'slug'             => 'sumaiya-akter',
                    'email'            => 'sumaiya@devteam.io',
                    'phone'            => '+880 1711-000008',
                    'bio'              => 'iOS developer crafting smooth, native Swift apps with a strong focus on UX, performance, and App Store guidelines. 5 published apps on the App Store.',
                    'github_url'       => 'https://github.com/sumaiyaakter',
                    'linkedin_url'     => 'https://linkedin.com/in/sumaiyaakter',
                    'skills'           => ['Swift', 'SwiftUI', 'UIKit', 'CoreData', 'Combine', 'Xcode', 'TestFlight'],
                    'experience_years' => 4,
                    'status'           => 'published',
                    'sort_order'       => 8,
                ],
                'roles' => ['ios-developer', 'mobile-app-developer'],
            ],
            [
                'data' => [
                    'name'             => 'Rezaul Karim',
                    'slug'             => 'rezaul-karim',
                    'email'            => 'rezaul@devteam.io',
                    'phone'            => '+880 1711-000009',
                    'bio'              => 'Java developer with enterprise experience in Spring Boot and microservices architecture. Worked on banking and ERP systems. Strong in system design and patterns.',
                    'github_url'       => 'https://github.com/rezaulkarim',
                    'linkedin_url'     => 'https://linkedin.com/in/rezaulkarim',
                    'skills'           => ['Java', 'Spring Boot', 'Hibernate', 'Microservices', 'Kafka', 'Oracle DB', 'Maven'],
                    'experience_years' => 8,
                    'status'           => 'draft',   // not published yet
                    'sort_order'       => 9,
                ],
                'roles' => ['java-developer', 'backend-developer'],
            ],
            [
                'data' => [
                    'name'             => 'Lamia Sultana',
                    'slug'             => 'lamia-sultana',
                    'email'            => 'lamia@devteam.io',
                    'phone'            => '+880 1711-000010',
                    'bio'              => 'Full-stack developer with a knack for building end-to-end e-commerce and SaaS platforms. Comfortable across the entire stack from database schema design to deployment.',
                    'github_url'       => 'https://github.com/lamiasultana',
                    'linkedin_url'     => 'https://linkedin.com/in/lamiasultana',
                    'portfolio_url'    => 'https://lamia.io',
                    'skills'           => ['Laravel', 'React', 'MySQL', 'TailwindCSS', 'Redis', 'Vue.js', 'Stripe API'],
                    'experience_years' => 3,
                    'status'           => 'published',
                    'sort_order'       => 10,
                ],
                'roles' => ['full-stack-developer', 'frontend-developer'],
            ],
        ];

        foreach ($developers as $entry) {
            $dev = Developer::firstOrCreate(
                ['slug' => $entry['data']['slug']],
                $entry['data']
            );

            // Sync roles by slug → id mapping
            $roleIds = collect($entry['roles'])
                ->map(fn ($slug) => $roles[$slug] ?? null)
                ->filter()
                ->values()
                ->toArray();

            $dev->roles()->sync($roleIds);

            $this->command->info("  ✓ {$dev->name} [{$dev->status}] — roles: " . implode(', ', $entry['roles']));
        }

        $this->command->info('✓ ' . count($developers) . ' developers seeded successfully.');
    }
}
