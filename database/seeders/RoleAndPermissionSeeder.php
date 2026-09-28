<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.suspend',

            // Role & Permission Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.assign',

            // Website & CMS
            'website.view',
            'website.edit',
            'homepage.view',
            'homepage.edit',
            'homepage.publish',
            'theme.view',
            'theme.edit',

            // Media
            'media.view',
            'media.upload',
            'media.edit',
            'media.delete',

            // Future Modules Prepared Foundations
            'members.view',
            'members.create',
            'members.edit',
            'members.delete',

            'volunteers.view',
            'volunteers.create',
            'volunteers.edit',
            'volunteers.delete',

            'communities.view',
            'communities.create',
            'communities.edit',
            'communities.delete',

            'events.view',
            'events.create',
            'events.edit',
            'events.delete',
            'events.publish',

            'campaigns.view',
            'campaigns.create',
            'campaigns.edit',
            'campaigns.delete',
            'campaigns.publish',

            'donations.view',
            'donations.export',

            'reports.view',
            'reports.export',

            // Security & Auditing
            'audit_logs.view',
            'login_histories.view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 1. Super Admin (Full permissions)
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all());

        // 2. Admin
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'users.view', 'users.create', 'users.edit',
            'website.view', 'website.edit', 'homepage.view', 'homepage.edit', 'homepage.publish', 'theme.view',
            'media.view', 'media.upload', 'media.edit',
            'members.view', 'members.create', 'members.edit',
            'volunteers.view', 'volunteers.create', 'volunteers.edit',
            'communities.view', 'communities.create', 'communities.edit',
            'events.view', 'events.create', 'events.edit', 'events.publish',
            'campaigns.view', 'campaigns.create', 'campaigns.edit', 'campaigns.publish',
            'donations.view', 'reports.view', 'reports.export',
            'audit_logs.view', 'login_histories.view',
        ]);

        // 3. Overall Leader
        $overallLeader = Role::firstOrCreate(['name' => 'Overall Leader', 'guard_name' => 'web']);
        $overallLeader->syncPermissions([
            'members.view', 'members.create', 'members.edit',
            'volunteers.view', 'volunteers.create', 'volunteers.edit',
            'communities.view',
            'events.view', 'events.create', 'events.edit',
            'campaigns.view', 'campaigns.create',
            'reports.view',
        ]);

        // 4. Community Leader
        $communityLeader = Role::firstOrCreate(['name' => 'Community Leader', 'guard_name' => 'web']);
        $communityLeader->syncPermissions([
            'members.view', 'members.create',
            'volunteers.view',
            'communities.view',
            'events.view',
            'campaigns.view',
        ]);

        // 5. Volunteer
        $volunteer = Role::firstOrCreate(['name' => 'Volunteer', 'guard_name' => 'web']);
        $volunteer->syncPermissions([
            'events.view',
            'campaigns.view',
        ]);

        // 6. Member
        $member = Role::firstOrCreate(['name' => 'Member', 'guard_name' => 'web']);
        $member->syncPermissions([
            'events.view',
            'campaigns.view',
        ]);
    }
}
