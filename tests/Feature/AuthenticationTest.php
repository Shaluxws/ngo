<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('NGO Portal Login');
    }

    public function test_valid_active_user_can_login(): void
    {
        $user = User::create([
            'name' => 'Test Leader',
            'email' => 'leader@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);
        $user->assignRole('Community Leader');

        $response = $this->post('/login', [
            'email' => 'leader@example.com',
            'password' => 'Password@12345',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($user);
    }

    public function test_inactive_user_cannot_login(): void
    {
        $user = User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'inactive',
        ]);

        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'Password@12345',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_invalid_password_is_rejected(): void
    {
        $user = User::create([
            'name' => 'Valid User',
            'email' => 'valid@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'valid@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::create([
            'name' => 'Login User',
            'email' => 'logout@example.com',
            'password' => bcrypt('Password@12345'),
            'status' => 'active',
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
