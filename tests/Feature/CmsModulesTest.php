<?php

namespace Tests\Feature;

use App\Livewire\Admin\MediaLibrary;
use App\Livewire\Admin\ThemeEditor;
use App\Livewire\Admin\WebsiteSettings;
use App\Models\Media;
use App\Models\SiteSetting;
use App\Models\ThemeSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class CmsModulesTest extends TestCase
{
    use DatabaseTransactions;

    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'admin_test2@nanbanfoundation.org.in'],
            [
                'name' => 'Super Admin Test 2',
                'phone' => '+919442000002',
                'password' => bcrypt('SuperPassword123!'),
                'status' => 'active',
            ]
        );
        $this->superAdmin->assignRole('Super Admin');
    }

    public function test_theme_editor_loads_stored_values_and_persists(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(ThemeEditor::class)
            ->assertSet('theme.primary_color', '#047857')
            ->assertSet('theme.heading_font', 'Plus Jakarta Sans')
            ->set('theme.primary_color', '#15803D')
            ->call('saveTheme');

        $this->assertEquals('#15803D', ThemeSetting::get('primary_color'));
    }

    public function test_website_settings_loads_stored_values_and_persists(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(WebsiteSettings::class)
            ->assertSet('settings.site_name', 'NANBAN SOCIAL FOUNDATION')
            ->set('settings.site_name', 'NANBAN TAMIL NADU FOUNDATION')
            ->call('saveGeneral');

        $this->assertEquals('NANBAN TAMIL NADU FOUNDATION', SiteSetting::get('site_name'));
    }

    public function test_media_library_page_renders_cleanly(): void
    {
        $response = $this->actingAs($this->superAdmin)->get('/admin/website/media');
        $response->assertStatus(200);
        $response->assertSee('Media Library');
    }

    public function test_media_upload_and_delete_workflow(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test_banner.jpg', 500, 'image/jpeg');

        Livewire::actingAs($this->superAdmin)
            ->test(MediaLibrary::class)
            ->set('uploadFiles', [$file]);

        $media = Media::where('original_name', 'test_banner.jpg')->first();
        $this->assertNotNull($media);
        $this->assertEquals('test_banner.jpg', $media->original_name);

        Livewire::actingAs($this->superAdmin)
            ->test(MediaLibrary::class)
            ->call('deleteMedia', $media->id);

        $this->assertNull(Media::find($media->id));
    }
}
