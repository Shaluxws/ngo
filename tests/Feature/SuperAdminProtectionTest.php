<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\SuperAdminProtectionService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SuperAdminProtectionTest extends TestCase
{
    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_single_active_super_admin_cannot_be_deleted(): void
    {
        // Clear existing super admins
        User::role('Super Admin')->delete();

        $soleAdmin = User::create([
            'name' => 'Sole Admin',
            'email' => 'sole@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);
        $soleAdmin->assignRole('Super Admin');

        $this->assertFalse(SuperAdminProtectionService::canDeleteUser($soleAdmin));
        $this->assertFalse(SuperAdminProtectionService::canDeactivateUser($soleAdmin));
        $this->assertFalse(SuperAdminProtectionService::canRemoveSuperAdminRole($soleAdmin));
    }

    public function test_super_admin_can_be_deleted_when_multiple_active_super_admins_exist(): void
    {
        // Clear existing super admins
        User::role('Super Admin')->delete();

        $admin1 = User::create([
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);
        $admin1->assignRole('Super Admin');

        $admin2 = User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);
        $admin2->assignRole('Super Admin');

        $this->assertTrue(SuperAdminProtectionService::canDeleteUser($admin1));
        $this->assertTrue(SuperAdminProtectionService::canDeactivateUser($admin1));
        $this->assertTrue(SuperAdminProtectionService::canRemoveSuperAdminRole($admin1));
    }
}
