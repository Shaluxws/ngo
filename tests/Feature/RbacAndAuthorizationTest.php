<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RbacAndAuthorizationTest extends TestCase
{
    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_super_admin_can_access_all_admin_modules(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin Tester',
            'email' => 'supertest@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);
        $superAdmin->assignRole('Super Admin');

        $this->actingAs($superAdmin);

        $this->get('/admin')->assertStatus(200);
        $this->get('/admin/users')->assertStatus(200);
        $this->get('/admin/roles')->assertStatus(200);
        $this->get('/admin/website/homepage')->assertStatus(200);
        $this->get('/admin/website/theme')->assertStatus(200);
        $this->get('/admin/website/media')->assertStatus(200);
        $this->get('/admin/security/audit-logs')->assertStatus(200);
    }

    public function test_volunteer_role_cannot_access_user_management(): void
    {
        $volunteer = User::create([
            'name' => 'Volunteer User',
            'email' => 'volunteer@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);
        $volunteer->assignRole('Volunteer');

        $this->actingAs($volunteer);

        $response = $this->get('/admin/users');
        $response->assertStatus(403);
    }
}
