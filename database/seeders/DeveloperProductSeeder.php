<?php

namespace Database\Seeders;

use App\Models\Developer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DeveloperProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Linking developers with showcased products...');

        $developers = Developer::published()->get()->keyBy('slug');
        $products = Product::where('is_active', true)->get();

        if ($developers->isEmpty() || $products->isEmpty()) {
            $this->command->warn('Developers or Products are empty. Skipping.');
            return;
        }

        // Project assignments mapping by technology & role
        $roleMap = [
            'web_app' => [
                'full-stack-developer' => 'Full Stack Architect',
                'backend-developer'    => 'Backend API Engineer',
                'frontend-developer'   => 'Frontend & UI Engineer',
                'ui-ux-designer'       => 'UI/UX Designer',
            ],
            'android' => [
                'android-developer'    => 'Lead Android Engineer',
                'flutter-developer'    => 'Mobile App Architect',
                'backend-developer'    => 'REST API Backend',
                'ui-ux-designer'       => 'Mobile App Designer',
            ],
            'ios' => [
                'ios-developer'        => 'Lead iOS Engineer',
                'flutter-developer'    => 'Cross-Platform Specialist',
                'backend-developer'    => 'Cloud API Engineer',
            ],
            'custom' => [
                'full-stack-developer' => 'Systems Architect',
                'devops-engineer'      => 'DevOps & Deployment',
                'backend-developer'    => 'Microservice Engineer',
            ],
        ];

        foreach ($products as $index => $product) {
            $type = $product->type ?? 'web_app';
            $assignedDevs = [];

            // Assign 1 to 3 relevant developers to each product
            foreach ($developers as $dev) {
                $devRoleSlugs = $dev->roles->pluck('slug')->toArray();
                
                $matchRole = null;
                if ($type === 'android' && (in_array('android-developer', $devRoleSlugs) || in_array('flutter-developer', $devRoleSlugs))) {
                    $matchRole = in_array('flutter-developer', $devRoleSlugs) ? 'Mobile Flutter Architect' : 'Native Android Engineer';
                } elseif ($type === 'ios' && (in_array('ios-developer', $devRoleSlugs) || in_array('flutter-developer', $devRoleSlugs))) {
                    $matchRole = in_array('ios-developer', $devRoleSlugs) ? 'Lead iOS Engineer' : 'Cross-Platform Engineer';
                } elseif ($type === 'web_app' && (in_array('full-stack-developer', $devRoleSlugs) || in_array('frontend-developer', $devRoleSlugs) || in_array('backend-developer', $devRoleSlugs))) {
                    $matchRole = in_array('full-stack-developer', $devRoleSlugs) ? 'Full Stack Architect' : (in_array('frontend-developer', $devRoleSlugs) ? 'Frontend UI Specialist' : 'Backend & Database Engineer');
                } elseif (in_array('ui-ux-designer', $devRoleSlugs) && count($assignedDevs) < 2) {
                    $matchRole = 'UI/UX Interface Designer';
                }

                if ($matchRole && count($assignedDevs) < 3) {
                    $assignedDevs[$dev->id] = ['role_in_project' => $matchRole, 'sort_order' => count($assignedDevs) + 1];
                }
            }

            // If none matched, fallback to first 2 developers
            if (empty($assignedDevs)) {
                $fallback = $developers->take(2);
                foreach ($fallback as $fDev) {
                    $assignedDevs[$fDev->id] = ['role_in_project' => 'Software Engineer', 'sort_order' => 1];
                }
            }

            $product->developers()->sync($assignedDevs);
            $this->command->info("  ✓ Linked {$product->title} with " . count($assignedDevs) . " developers.");
        }

        $this->command->info('✓ All products successfully linked with developers.');
    }
}
