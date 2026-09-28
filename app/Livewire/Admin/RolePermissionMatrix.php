<?php

namespace App\Livewire\Admin;

use App\Services\AuditService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

#[Layout('layouts.admin')]
class RolePermissionMatrix extends Component
{
    public ?int $selectedRoleId = null;
    public string $newRoleName = '';
    public array $rolePermissions = [];

    public function mount(): void
    {
        $defaultRole = Role::where('name', '!=', 'Super Admin')->first() ?: Role::first();
        if ($defaultRole) {
            $this->selectRole($defaultRole->id);
        }
    }

    public function selectRole(int $roleId): void
    {
        $this->selectedRoleId = $roleId;
        $role = Role::with('permissions')->findOrFail($roleId);
        $this->rolePermissions = $role->permissions->pluck('name')->toArray();
    }

    public function togglePermission(string $permissionName): void
    {
        $role = Role::findOrFail($this->selectedRoleId);

        if ($role->name === 'Super Admin') {
            session()->flash('error', 'Super Admin always retains all permissions by system design.');
            return;
        }

        $oldPermissions = $role->permissions->pluck('name')->toArray();

        if (in_array($permissionName, $this->rolePermissions, true)) {
            $role->revokePermissionTo($permissionName);
            $this->rolePermissions = array_values(array_diff($this->rolePermissions, [$permissionName]));
            $action = "Revoked permission '{$permissionName}' from role '{$role->name}'";
        } else {
            $role->givePermissionTo($permissionName);
            $this->rolePermissions[] = $permissionName;
            $action = "Granted permission '{$permissionName}' to role '{$role->name}'";
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditService::log(
            $action,
            'roles',
            $role->id,
            ['permissions' => $oldPermissions],
            ['permissions' => $this->rolePermissions]
        );

        session()->flash('success', "Permission updated for role '{$role->name}'.");
    }

    public function createRole(): void
    {
        $this->validate([
            'newRoleName' => 'required|string|max:50|unique:roles,name',
        ]);

        $role = Role::create(['name' => trim($this->newRoleName), 'guard_name' => 'web']);

        AuditService::log(
            "Created new role '{$role->name}'",
            'roles',
            $role->id,
            null,
            ['name' => $role->name]
        );

        $this->newRoleName = '';
        $this->selectRole($role->id);
        session()->flash('success', "Role '{$role->name}' created successfully.");
    }

    public function deleteRole(int $roleId): void
    {
        $role = Role::findOrFail($roleId);

        if (in_array($role->name, ['Super Admin', 'Admin', 'Overall Leader', 'Community Leader', 'Volunteer', 'Member'], true)) {
            session()->flash('error', "System role '{$role->name}' is protected and cannot be deleted.");
            return;
        }

        $roleName = $role->name;
        $role->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        AuditService::log(
            "Deleted role '{$roleName}'",
            'roles',
            $roleId,
            ['name' => $roleName],
            null
        );

        $firstRole = Role::first();
        if ($firstRole) {
            $this->selectRole($firstRole->id);
        }

        session()->flash('success', "Role '{$roleName}' was deleted.");
    }

    public function render()
    {
        $roles = Role::withCount('permissions', 'users')->get();
        $selectedRole = $this->selectedRoleId ? Role::find($this->selectedRoleId) : null;

        // Group permissions logically for granular matrix presentation
        $permissionGroups = [
            'Users & Staff' => [
                'users.view' => 'View Users List & Profiles',
                'users.create' => 'Create New User Accounts',
                'users.edit' => 'Edit User Profiles & Passwords',
                'users.delete' => 'Delete User Accounts',
                'users.suspend' => 'Suspend or Deactivate Users',
            ],
            'Members Management' => [
                'members.view' => 'View Members Directory & Profiles',
                'members.create' => 'Register New Community Members',
                'members.edit' => 'Edit Member Profile Information',
                'members.delete' => 'Archive or Delete Member Records',
                'members.suspend' => 'Suspend or Change Member Status',
                'members.restore' => 'Restore Archived Member Records',
                'members.export' => 'Export Members Data (CSV)',
            ],
            'Volunteer Management' => [
                'volunteers.view' => 'View Volunteers Directory & Profiles',
                'volunteers.create' => 'Onboard / Register Volunteer Profiles',
                'volunteers.edit' => 'Edit Volunteer Skills, Interests & Availability',
                'volunteers.delete' => 'Archive or Delete Volunteer Profiles',
                'volunteers.approve' => 'Approve Pending Volunteer Applications',
                'volunteers.reject' => 'Reject Volunteer Applications with Reason',
                'volunteers.suspend' => 'Suspend or Change Volunteer Status',
                'volunteers.restore' => 'Restore Archived Volunteer Profiles',
                'volunteers.export' => 'Export Volunteers Directory (CSV)',
                'volunteers.participation.view' => 'View Volunteer Participation History',
                'volunteers.participation.create' => 'Log Volunteer Activity & Hours',
                'volunteers.participation.edit' => 'Edit Activity Log & Hours',
                'volunteers.participation.delete' => 'Delete Participation Logs',
            ],
            'Roles & Permissions' => [
                'roles.view' => 'View Roles & Permission Matrix',
                'roles.create' => 'Create Custom Roles',
                'roles.edit' => 'Modify Role Permissions',
                'roles.delete' => 'Delete Custom Roles',
                'permissions.view' => 'View System Permissions',
                'permissions.assign' => 'Assign Roles to Users',
            ],
            'Website & CMS' => [
                'website.view' => 'View General & SEO Settings',
                'website.edit' => 'Edit General & SEO Settings',
                'homepage.view' => 'View Homepage Editor',
                'homepage.edit' => 'Edit Homepage Sections & Content',
                'homepage.publish' => 'Publish Homepage Drafts to Live',
                'theme.view' => 'View Theme Colors & Fonts',
                'theme.edit' => 'Edit & Publish Theme Tokens',
            ],
            'Media Assets' => [
                'media.view' => 'Browse Media Library',
                'media.upload' => 'Upload New Images & Files',
                'media.edit' => 'Edit Media Metadata & Alt Text',
                'media.delete' => 'Delete Media Files',
            ],
            'Communities & Programs' => [
                'communities.view' => 'View Community Focal Points',
                'communities.create' => 'Add New Community Focal Points',
                'communities.edit' => 'Edit Community Details',
                'communities.delete' => 'Delete Community Records',
            ],
            'Events & Campaigns' => [
                'events.view' => 'View Events Schedule',
                'events.create' => 'Create Community Events',
                'events.edit' => 'Edit Event Details',
                'events.delete' => 'Delete Events',
                'events.publish' => 'Publish Events to Website',
                'campaigns.view' => 'View Campaigns',
                'campaigns.create' => 'Create Fundraiser Campaigns',
                'campaigns.edit' => 'Edit Campaign Goals & Content',
                'campaigns.delete' => 'Delete Campaigns',
                'campaigns.publish' => 'Publish Campaigns to Website',
            ],
            'Donations & Reports' => [
                'donations.view' => 'View Donation Summaries',
                'donations.export' => 'Export Donation Records',
                'reports.view' => 'View Organizational Reports',
                'reports.export' => 'Export Analytics & Reports',
            ],
            'Security & Auditing' => [
                'audit_logs.view' => 'View Security Audit Logs',
                'login_histories.view' => 'View User Login Histories',
            ],
        ];

        return view('livewire.admin.role-permission-matrix', [
            'roles' => $roles,
            'selectedRole' => $selectedRole,
            'permissionGroups' => $permissionGroups,
        ]);
    }
}
