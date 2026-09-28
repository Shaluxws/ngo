<?php

namespace Tests\Feature;

use App\Livewire\Admin\WebsiteSettings;
use App\Models\AuditLog;
use App\Models\Media;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class WebsiteSettingsBrandingAndSocialTest extends TestCase
{
    use DatabaseTransactions;

    protected User $superAdmin;
    protected User $regularUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'admin_branding_test@nanbanfoundation.org.in'],
            [
                'name' => 'Super Admin Branding',
                'phone' => '+919442000003',
                'password' => bcrypt('SuperPassword123!'),
                'status' => 'active',
            ]
        );
        $this->superAdmin->assignRole('Super Admin');

        $this->regularUser = User::firstOrCreate(
            ['email' => 'member_branding_test@nanbanfoundation.org.in'],
            [
                'name' => 'Regular Member',
                'phone' => '+919442011114',
                'password' => bcrypt('MemberPassword123!'),
                'status' => 'active',
            ]
        );
        $this->regularUser->assignRole('Member');
    }

    public function test_branding_and_social_tabs_accessible_to_super_admin(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['activeTab' => 'branding'])
            ->assertSee('BRANDING & LOGOS', false)
            ->assertSee('Header Brand Logo', false)
            ->assertSee('Footer Brand Logo', false)
            ->assertSee('Browser Tab Favicon', false)
            ->assertSee('Upload from Device', false);

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['activeTab' => 'social'])
            ->assertSee('OFFICIAL SOCIAL MEDIA CHANNELS', false)
            ->assertSee('+ Add Social Channel', false);
    }

    public function test_super_admin_can_upload_header_logo_from_device(): void
    {
        $file = UploadedFile::fake()->create('nanban_header_logo.png', 100, 'image/png');

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'branding'])
            ->set('headerLogoFile', $file)
            ->assertHasNoErrors()
            ->assertSet('settings.logo_url', function ($url) {
                return !empty($url) && str_contains($url, '/storage/media/');
            });

        $this->assertDatabaseHas('media', [
            'original_name' => 'nanban_header_logo.png',
        ]);

        $this->assertDatabaseHas('site_settings', [
            'key' => 'logo_url',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Uploaded new Header Brand Logo \'nanban_header_logo.png\'',
        ]);
    }

    public function test_super_admin_can_upload_footer_logo_and_favicon(): void
    {
        $footerFile = UploadedFile::fake()->create('nanban_footer_white.png', 80, 'image/png');
        $faviconFile = UploadedFile::fake()->create('favicon.ico', 32, 'image/x-icon');

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'branding'])
            ->set('footerLogoFile', $footerFile)
            ->assertHasNoErrors()
            ->set('faviconFile', $faviconFile)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('media', [
            'original_name' => 'nanban_footer_white.png',
        ]);

        $this->assertDatabaseHas('media', [
            'original_name' => 'favicon.ico',
        ]);
    }

    public function test_super_admin_can_remove_branding_image(): void
    {
        SiteSetting::set('logo_url', 'https://example.com/logo.png', 'branding', 'text');

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'branding'])
            ->call('removeBrandingImage', 'logo_url', 'Header Logo')
            ->assertSet('settings.logo_url', '');

        $this->assertEquals('', SiteSetting::get('logo_url'));

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'Removed Header Logo',
        ]);
    }

    public function test_super_admin_can_select_branding_from_media_library(): void
    {
        $media = Media::create([
            'file_name' => 'library_logo.webp',
            'original_name' => 'library_logo.webp',
            'file_path' => 'media/library_logo.webp',
            'disk' => 'public',
            'mime_type' => 'image/webp',
            'file_size' => 12450,
            'width' => 600,
            'height' => 200,
            'alt_text' => 'Library Logo',
            'title' => 'Library Logo',
            'uploaded_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'branding'])
            ->call('openMediaPicker', 'settings.logo_url')
            ->assertSet('showMediaPickerModal', true)
            ->call('selectMediaItem', $media->url)
            ->assertSet('showMediaPickerModal', false)
            ->assertSet('settings.logo_url', $media->url);

        $this->assertEquals($media->url, SiteSetting::get('logo_url'));
    }

    public function test_social_media_crud_operations(): void
    {
        // 1. Create Social Channel
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'social'])
            ->call('openSocialModal')
            ->assertSet('showSocialModal', true)
            ->set('socialForm.platform', 'Instagram')
            ->set('socialForm.url', 'https://instagram.com/nanban_foundation_tn')
            ->set('socialForm.icon', 'instagram')
            ->set('socialForm.sort_order', 1)
            ->set('socialForm.is_active', true)
            ->call('saveSocialLink')
            ->assertSet('showSocialModal', false);

        $this->assertDatabaseHas('social_links', [
            'platform' => 'Instagram',
            'url' => 'https://instagram.com/nanban_foundation_tn',
            'is_active' => true,
        ]);

        $link = SocialLink::where('platform', 'Instagram')->firstOrFail();

        // 2. Edit Social Channel
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'social'])
            ->call('openSocialModal', $link->id)
            ->assertSet('editingSocialLinkId', $link->id)
            ->set('socialForm.url', 'https://instagram.com/nanban_official')
            ->call('saveSocialLink')
            ->assertSet('showSocialModal', false);

        $this->assertDatabaseHas('social_links', [
            'id' => $link->id,
            'url' => 'https://instagram.com/nanban_official',
        ]);

        // 3. Toggle Status
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'social'])
            ->call('toggleSocialLink', $link->id);

        $this->assertFalse((bool) $link->fresh()->is_active);

        // 4. Delete with confirmation
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['tab' => 'social'])
            ->call('confirmDeleteSocial', $link->id, $link->platform)
            ->assertSet('confirmingDeleteSocial', true)
            ->call('performDeleteSocial')
            ->assertSet('confirmingDeleteSocial', false);

        $this->assertDatabaseMissing('social_links', [
            'id' => $link->id,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => "Deleted social media link 'Instagram'",
        ]);
    }

    public function test_social_media_reordering(): void
    {
        SocialLink::query()->delete();

        $link1 = SocialLink::create([
            'platform' => 'Facebook',
            'url' => 'https://facebook.com/nanban',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $link2 = SocialLink::create([
            'platform' => 'YouTube',
            'url' => 'https://youtube.com/@nanban',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class, ['activeTab' => 'social'])
            ->call('moveSocialLink', $link2->id, 'up');

        $this->assertEquals(1, $link2->fresh()->sort_order);
    }

    public function test_public_website_renders_configured_branding_and_active_social_links(): void
    {
        SiteSetting::set('logo_url', 'https://example.com/custom_header_logo.png', 'branding', 'text');
        SiteSetting::set('footer_logo_url', 'https://example.com/custom_footer_logo.png', 'branding', 'text');

        SocialLink::query()->delete();
        SocialLink::create([
            'platform' => 'WhatsApp Channel',
            'url' => 'https://wa.me/919442012345',
            'icon' => 'phone',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        SocialLink::create([
            'platform' => 'Hidden Platform',
            'url' => 'https://hidden.example.com',
            'icon' => 'globe',
            'sort_order' => 2,
            'is_active' => false,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        // Header and Footer custom logos rendered
        $response->assertSee('https://example.com/custom_header_logo.png', false);
        $response->assertSee('https://example.com/custom_footer_logo.png', false);

        // Active social link rendered
        $response->assertSee('https://wa.me/919442012345', false);

        // Inactive social link NOT rendered
        $response->assertDontSee('https://hidden.example.com', false);

        // Strict developer branding check: zero ShanLux branding visible on public page
        $content = $response->getContent();
        $this->assertStringNotContainsString('ShanLux IT Services', $content);
        $this->assertStringNotContainsString('ShanLux NGO Platform', $content);
    }
}
