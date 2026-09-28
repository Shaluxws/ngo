<?php

namespace Tests\Feature;

use App\Livewire\Admin\WebsiteSettings;
use App\Models\AuditLog;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Tests\TestCase;

class WebsiteSettingsTopBarTest extends TestCase
{
    use DatabaseTransactions;

    protected User $superAdmin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'admin_test_topbar@nanbanfoundation.org.in'],
            [
                'name' => 'Super Admin Test',
                'phone' => '+919442000001',
                'password' => bcrypt('SuperPassword123!'),
                'status' => 'active',
            ]
        );
        $this->superAdmin->assignRole('Super Admin');

        $this->regularUser = User::firstOrCreate(
            ['email' => 'member_test_topbar@nanbanfoundation.org.in'],
            [
                'name' => 'Regular Member',
                'phone' => '+919442011112',
                'password' => bcrypt('MemberPassword123!'),
                'status' => 'active',
            ]
        );
        $this->regularUser->assignRole('Member');
    }

    public function test_unauthorized_user_is_forbidden_from_website_settings(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin/website/settings');
        $response->assertStatus(403);
    }

    public function test_super_admin_can_access_website_settings(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/website/settings');
        $response->assertStatus(200);
        $response->assertSee('Website & Organization Settings', false);
        $response->assertSee('Top Bar & Announcement', false);
    }

    public function test_top_bar_settings_load_and_populate_admin_form(): void
    {
        SiteSetting::set('top_bar_badge', 'Community Action', 'top_bar');
        SiteSetting::set('top_bar_text', 'Serving 38 Districts of Tamil Nadu', 'top_bar');
        SiteSetting::set('top_bar_phone', '+91 94420 99999', 'top_bar');
        SiteSetting::set('top_bar_email', 'info@nanbanfoundation.org.in', 'top_bar');
        SiteSetting::set('top_bar_enabled', '1', 'top_bar', 'boolean');
        SiteSetting::set('top_bar_phone_enabled', '1', 'top_bar', 'boolean');
        SiteSetting::set('top_bar_email_enabled', '1', 'top_bar', 'boolean');

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->assertSet('topBarBadge', 'Community Action')
            ->assertSet('topBarText', 'Serving 38 Districts of Tamil Nadu')
            ->assertSet('topBarPhone', '+91 94420 99999')
            ->assertSet('topBarEmail', 'info@nanbanfoundation.org.in')
            ->assertSet('topBarEnabled', true)
            ->assertSet('topBarPhoneEnabled', true)
            ->assertSet('topBarEmailEnabled', true);
    }

    public function test_super_admin_can_update_top_bar_settings_and_persist_to_mysql(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->set('topBarBadge', 'Special Relief Drive')
            ->set('topBarText', 'Statewide Summer Heat Support Camp Active')
            ->set('topBarPhone', '+91 94420 88888')
            ->set('topBarEmail', 'relief@nanbanfoundation.org.in')
            ->set('topBarEnabled', true)
            ->set('topBarPhoneEnabled', true)
            ->set('topBarEmailEnabled', true)
            ->call('saveTopBar');

        $this->assertEquals('Special Relief Drive', SiteSetting::get('top_bar_badge'));
        $this->assertEquals('Statewide Summer Heat Support Camp Active', SiteSetting::get('top_bar_text'));
        $this->assertEquals('+91 94420 88888', SiteSetting::get('top_bar_phone'));
        $this->assertEquals('relief@nanbanfoundation.org.in', SiteSetting::get('top_bar_email'));
        $this->assertEquals('1', SiteSetting::get('top_bar_enabled'));

        // Verify public website renders updated values
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Special Relief Drive');
        $response->assertSee('Statewide Summer Heat Support Camp Active');
        $response->assertSee('+91 94420 88888');
        $response->assertSee('relief@nanbanfoundation.org.in');

        // Verify audit log entry was created
        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Updated top bar & announcement settings',
            'user_id' => $this->superAdmin->id,
        ]);
    }

    public function test_super_admin_can_disable_phone_and_email_visibility(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->set('topBarBadge', 'Dignity in Service')
            ->set('topBarText', 'Grassroots Social Transformation')
            ->set('topBarPhone', '+91 94420 12345')
            ->set('topBarEmail', 'contact@nanbanfoundation.org.in')
            ->set('topBarEnabled', true)
            ->set('topBarPhoneEnabled', false)
            ->set('topBarEmailEnabled', false)
            ->call('saveTopBar');

        $this->assertEquals('0', SiteSetting::get('top_bar_phone_enabled'));
        $this->assertEquals('0', SiteSetting::get('top_bar_email_enabled'));

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Dignity in Service');
        $response->assertDontSee('aria-label="Call', false);
        $response->assertDontSee('aria-label="Email', false);
    }

    public function test_super_admin_can_disable_entire_top_bar(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->set('topBarEnabled', false)
            ->call('saveTopBar');

        $this->assertEquals('0', SiteSetting::get('top_bar_enabled'));

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Tamil Nadu Grassroots Community Management & Social Impact');
    }

    public function test_empty_badge_and_announcement_are_handled_gracefully(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->set('topBarBadge', '')
            ->set('topBarText', '')
            ->set('topBarPhone', '+91 94420 77777')
            ->set('topBarEmail', 'help@nanbanfoundation.org.in')
            ->set('topBarEnabled', true)
            ->set('topBarPhoneEnabled', true)
            ->set('topBarEmailEnabled', true)
            ->call('saveTopBar');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('+91 94420 77777');
        $response->assertSee('help@nanbanfoundation.org.in');
        $this->assertStringNotContainsString('ShanLux NGO Platform', $response->getContent());
    }

    public function test_admin_dashboard_link_remains_fixed_and_secure(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee(route('admin.dashboard'));
        $response->assertSee('Admin Dashboard');

        // As authenticated admin
        $adminResponse = $this->actingAs($this->superAdmin)->get('/');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee(route('admin.dashboard'));
        $adminResponse->assertSee('Admin Dashboard');
    }

    public function test_top_bar_tab_query_parameter_sets_active_tab(): void
    {
        Livewire::withQueryParams(['tab' => 'top_bar'])
            ->actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->assertSet('activeTab', 'top_bar')
            ->assertSee('TOP BAR & ANNOUNCEMENT', false);
    }

    public function test_super_admin_can_update_top_bar_appearance_colors(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->set('topBarBgColor', '#0F766E')
            ->set('topBarTextColor', '#F8FAFC')
            ->set('topBarBadgeColor', '#10B981')
            ->set('topBarLinkColor', '#38BDF8')
            ->call('saveTopBar');

        $this->assertEquals('#0F766E', SiteSetting::get('top_bar_background_color'));
        $this->assertEquals('#F8FAFC', SiteSetting::get('top_bar_text_color'));
        $this->assertEquals('#10B981', SiteSetting::get('top_bar_badge_color'));
        $this->assertEquals('#38BDF8', SiteSetting::get('top_bar_link_color'));

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('background-color: #0F766E', false);
        $response->assertSee('color: #F8FAFC', false);
        $response->assertSee('background-color: #10B981', false);
    }

    public function test_top_bar_color_validation_rejects_invalid_hex_codes(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->set('topBarBgColor', 'invalid-color-code')
            ->call('saveTopBar')
            ->assertHasErrors(['topBarBgColor']);
    }

    public function test_top_bar_color_reset_restores_default_colors(): void
    {
        SiteSetting::set('top_bar_background_color', '#990000', 'top_bar');

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->set('activeTab', 'top_bar')
            ->call('resetTopBarColors')
            ->assertSet('topBarBgColor', '#166534')
            ->assertSet('topBarTextColor', '#FFFFFF')
            ->assertSet('topBarBadgeColor', '#F59E0B')
            ->assertSet('topBarLinkColor', '#FEF08A');

        $this->assertEquals('#166534', SiteSetting::get('top_bar_background_color'));
        $this->assertEquals('#FFFFFF', SiteSetting::get('top_bar_text_color'));
    }
}
