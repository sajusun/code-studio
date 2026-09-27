<?php

namespace Database\Seeders;

use App\Models\ClientProject;
use App\Models\Developer;
use App\Models\ProjectMessage;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientPortalSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding demo Client Portal accounts & projects...');

        // 1. Create Demo Client User
        $clientUser = User::firstOrCreate(
            ['email' => 'client@demo.com'],
            [
                'name'     => 'Alexander Wright',
                'password' => Hash::make('password123'),
                'role'     => 'client',
            ]
        );

        $developers = Developer::published()->get();
        $sadia = $developers->firstWhere('slug', 'sadia-islam') ?? $developers->first();
        $arif = $developers->firstWhere('slug', 'arif-hossain') ?? $developers->skip(1)->first();
        $rakibul = $developers->firstWhere('slug', 'rakibul-islam') ?? $developers->last();

        // 2. Project 1: FoodieExpress Custom Mobile & Web Suite
        $proj1 = ClientProject::firstOrCreate(
            ['project_code' => 'PRJ-2026-081'],
            [
                'user_id'           => $clientUser->id,
                'title'             => 'FoodieExpress — Custom Restaurant & Delivery App',
                'slug'              => 'foodieexpress-custom-restaurant-app',
                'description'       => 'Customized on-demand food delivery mobile app with real-time GPS courier tracking, multi-vendor restaurant web dashboard, Stripe payment gateway, and customer iOS/Android applications.',
                'status'            => 'in_progress',
                'progress_percent'  => 65,
                'platform'          => 'Flutter Mobile (iOS & Android) + Laravel API',
                'budget'            => 2800.00,
                'currency'          => 'USD',
                'start_date'        => now()->subDays(18),
                'delivery_deadline' => now()->addDays(12),
                'staging_url'       => 'http://localhost:3000/products/food-delivery-flutter-app-laravel-backend',
                'github_repo_url'   => 'https://github.com/example/foodieexpress-custom',
                'apk_build_url'     => 'http://backend.test/storage/downloads/foodieexpress-v1.2-beta.apk',
                'figma_url'         => 'https://www.figma.com',
                'notes'             => 'Client requested dark theme default and Bengali + English bilingual support.',
            ]
        );

        // Assign Developers to Project 1
        if ($sadia && $arif) {
            $proj1->developers()->sync([
                $sadia->id => ['role_in_project' => 'Lead Flutter Architect'],
                $arif->id  => ['role_in_project' => 'Backend API & Database Lead'],
            ]);
        }

        // Milestones for Project 1
        $milestonesProj1 = [
            [
                'title'        => 'Milestone 1: Requirement Analysis & Figma UI Design System',
                'description'  => 'Completed wireframes, design tokens, restaurant menu flows, and client branding approval.',
                'status'       => 'completed',
                'due_date'     => now()->subDays(14),
                'completed_at' => now()->subDays(13),
                'sort_order'   => 1,
            ],
            [
                'title'        => 'Milestone 2: Backend REST APIs & Real-time Order WebSockets',
                'description'  => 'Developed Laravel 12 microservice endpoints, Reverb WebSocket channels for live courier status, and Sanctum auth.',
                'status'       => 'completed',
                'due_date'     => now()->subDays(5),
                'completed_at' => now()->subDays(4),
                'sort_order'   => 2,
            ],
            [
                'title'        => 'Milestone 3: Flutter Cross-Platform App & Cart Integration',
                'description'  => 'Currently integrating location services, GPS map markers, and Stripe payment sheet.',
                'status'       => 'in_progress',
                'due_date'     => now()->addDays(4),
                'completed_at' => null,
                'sort_order'   => 3,
            ],
            [
                'title'        => 'Milestone 4: QA Testing, Staging Release & App Store Deployment',
                'description'  => 'Full end-to-end regression testing, TestFlight build upload, and Google Play Store APK release.',
                'status'       => 'pending',
                'due_date'     => now()->addDays(12),
                'completed_at' => null,
                'sort_order'   => 4,
            ],
        ];

        foreach ($milestonesProj1 as $m) {
            $proj1->milestones()->firstOrCreate(['title' => $m['title']], $m);
        }

        // Messages for Project 1
        ProjectMessage::firstOrCreate(
            ['client_project_id' => $proj1->id, 'message' => "Welcome to your CodeStudio Client Workspace! Milestone 1 & 2 have been completed."],
            [
                'user_id'       => null,
                'sender_type'   => 'system',
                'sender_name'   => 'CodeStudio Automation',
                'sender_avatar' => null,
            ]
        );

        ProjectMessage::firstOrCreate(
            ['client_project_id' => $proj1->id, 'message' => "Hi Alexander, we've updated the staging build with the new restaurant search filters and food menu categories. Please check out the staging URL."],
            [
                'user_id'       => null,
                'sender_type'   => 'developer',
                'sender_name'   => 'Sadia Islam (Flutter Lead)',
                'sender_avatar' => null,
            ]
        );

        ProjectMessage::firstOrCreate(
            ['client_project_id' => $proj1->id, 'message' => "Looks fantastic Sadia! The smooth transitions on mobile feel very snappy. Looking forward to testing the Stripe checkout next."],
            [
                'user_id'       => $clientUser->id,
                'sender_type'   => 'client',
                'sender_name'   => 'Alexander Wright',
                'sender_avatar' => null,
            ]
        );

        // 3. Project 2: CryptoPay Merchant Gateway
        $proj2 = ClientProject::firstOrCreate(
            ['project_code' => 'PRJ-2026-094'],
            [
                'user_id'           => $clientUser->id,
                'title'             => 'CryptoPay — Non-Custodial Merchant Payment Integration',
                'slug'              => 'cryptopay-merchant-payment-gateway',
                'description'       => 'Custom crypto invoice widget, Webhook dispatcher, and merchant dashboard supporting USDT, Bitcoin, and Ethereum.',
                'status'            => 'review',
                'progress_percent'  => 90,
                'platform'          => 'Next.js 15 Web Dashboard + Laravel Microservice',
                'budget'            => 3500.00,
                'currency'          => 'USD',
                'start_date'        => now()->subDays(30),
                'delivery_deadline' => now()->addDays(3),
                'staging_url'       => 'http://localhost:3000/products/invozen-multi-tenant-cloud-invoicing-billing-saas',
                'github_repo_url'   => 'https://github.com/example/cryptopay-custom',
                'notes'             => 'Pending final client review of webhook retry logic.',
            ]
        );

        if ($rakibul) {
            $proj2->developers()->sync([
                $rakibul->id => ['role_in_project' => 'Full-Stack Blockchain Engineer'],
            ]);
        }

        $this->command->info('✓ Client Portal seeded successfully! Login: client@demo.com / password123');
    }
}
