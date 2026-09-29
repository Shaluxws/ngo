<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Community;
use App\Models\District;
use App\Models\Event;
use App\Models\Program;
use App\Models\Story;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ResponsiveAndPublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_public_multi_page_routes_render_successfully(): void
    {
        $publicRoutes = [
            '/',
            '/about',
            '/programs',
            '/impact',
            '/stories',
            '/events',
            '/gallery',
            '/contact',
            '/volunteer',
        ];

        foreach ($publicRoutes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_public_program_detail_page_renders_successfully(): void
    {
        $program = Activity::first();
        if ($program) {
            $slug = \Illuminate\Support\Str::slug($program->title);
            $response = $this->get("/programs/{$slug}");
            $response->assertStatus(200);
            $response->assertSee($program->title);
        } else {
            $this->assertTrue(true);
        }
    }

    public function test_public_contact_form_submits_successfully(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.submit'), [
            'name' => 'Ramesh Kumar',
            'email' => 'ramesh@example.com',
            'phone' => '+91 98400 12345',
            'subject' => 'Volunteering Inquiry',
            'message' => 'I would like to volunteer in Coimbatore weekend education drives.',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('contact_success');
    }

    public function test_authenticated_admin_can_access_all_responsive_admin_views(): void
    {
        $admin = User::factory()->create(['status' => 'active']);
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin->assignRole($superAdminRole);

        $adminRoutes = [
            route('admin.dashboard'),
            route('admin.members.index'),
            route('admin.volunteers.index'),
            route('admin.users'),
            route('admin.roles'),
            route('admin.website.homepage'),
            route('admin.website.media'),
            route('admin.website.theme'),
            route('admin.website.settings'),
            route('admin.security.audit-logs'),
            route('admin.security.login-history'),
        ];

        foreach ($adminRoutes as $route) {
            $response = $this->actingAs($admin)->get($route);
            $response->assertStatus(200);
        }
    }
}
