<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Permissions are grouped by resource.
     * Format: resource.action
     */
    private array $permissions = [
        // ── Rooms ──────────────────────────────────────────────
        'rooms.view',
        'rooms.create',
        'rooms.edit',
        'rooms.delete',
        'rooms.update_status',

        // ── Room Types ─────────────────────────────────────────
        'room_types.view',
        'room_types.create',
        'room_types.edit',
        'room_types.delete',

        // ── Guests ─────────────────────────────────────────────
        'guests.view',
        'guests.create',
        'guests.edit',
        'guests.delete',

        // ── Reservations ───────────────────────────────────────
        'reservations.view',
        'reservations.create',
        'reservations.cancel',
        'reservations.check_in',
        'reservations.check_out',

        // ── Billing / Invoices ─────────────────────────────────
        'invoices.view',
        'invoices.issue',
        'invoices.add_service',
        'invoices.record_payment',

        // ── Users & Roles ──────────────────────────────────────
        'users.view',
        'users.create',
        'users.edit',
        'users.delete',
        'roles.view',
        'roles.assign',

        // ── Support ────────────────────────────────────────────
        'support.view',
        'support.reply',
        'support.manage',

        // ── Newsletter ─────────────────────────────────────────
        'newsletter.view',

        // ── Settings ───────────────────────────────────────────
        'settings.view',
        'settings.edit',

        // ── Reports / Audit ────────────────────────────────────
        'audit.view',
        'reports.view',
    ];

    /**
     * Roles and their granted permissions.
     */
    private array $roles = [
        /**
         * Super-admin: owns everything.
         * Spatie uses a special gate-bypass for this role,
         * so we don't need to list permissions explicitly,
         * but we still assign all of them for clarity.
         */
        'admin' => '*',

        /**
         * Manager: manages rooms, reservations, billing, guests,
         * can view reports but cannot manage users/roles or settings.
         */
        'manager' => [
            'rooms.view', 'rooms.create', 'rooms.edit', 'rooms.update_status',
            'room_types.view', 'room_types.create', 'room_types.edit',
            'guests.view', 'guests.create', 'guests.edit',
            'reservations.view', 'reservations.create', 'reservations.cancel',
            'reservations.check_in', 'reservations.check_out',
            'invoices.view', 'invoices.issue', 'invoices.add_service', 'invoices.record_payment',
            'support.view', 'support.reply',
            'newsletter.view',
            'audit.view',
            'reports.view',
        ],

        /**
         * Receptionist: daily front-desk operations.
         * No billing management, no settings, no user management.
         */
        'receptionist' => [
            'rooms.view', 'rooms.update_status',
            'room_types.view',
            'guests.view', 'guests.create', 'guests.edit',
            'reservations.view', 'reservations.create', 'reservations.cancel',
            'reservations.check_in', 'reservations.check_out',
            'invoices.view',
            'support.view',
        ],

        /**
         * Help Desk: customer support focus.
         * Can see guests, reservations and manage support conversations.
         */
        'help_desk' => [
            'guests.view',
            'reservations.view',
            'invoices.view',
            'support.view', 'support.reply', 'support.manage',
            'newsletter.view',
        ],

        /**
         * Staff: basic operational access.
         * View-only on most resources, can update room status.
         */
        'staff' => [
            'rooms.view', 'rooms.update_status',
            'room_types.view',
            'guests.view',
            'reservations.view',
            'invoices.view',
        ],

        /**
         * Customer / Guest: for future guest portal.
         * Very limited – can only view their own data.
         */
        'customer' => [
            'reservations.view',
            'invoices.view',
        ],
    ];

    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── 1. Create all permissions ──────────────────────────────────────
        $this->command->info('Creating permissions...');
        foreach ($this->permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }
        $this->command->info('✓ ' . count($this->permissions) . ' permissions created.');

        // ── 2. Create roles and assign permissions ─────────────────────────
        $this->command->info('Creating roles...');
        foreach ($this->roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            if ($perms === '*') {
                $role->syncPermissions(Permission::all());
            } else {
                $role->syncPermissions($perms);
            }

            $this->command->info("  ✓ Role [{$roleName}] — " . ($perms === '*' ? 'all permissions' : count($perms) . ' permissions'));
        }

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
