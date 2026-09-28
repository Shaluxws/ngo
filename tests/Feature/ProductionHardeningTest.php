<?php

namespace Tests\Feature;

use App\Livewire\Admin\MediaLibrary;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProductionHardeningTest extends TestCase
{
    protected User $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);

        $this->superAdmin = User::firstOrCreate(
            ['email' => 'hardening_superadmin@nanbanfoundation.org.in'],
            [
                'name' => 'Hardening Super Admin',
                'password' => bcrypt('StrongSecret123!'),
                'status' => 'active',
            ]
        );

        if (!$this->superAdmin->hasRole('Super Admin')) {
            $this->superAdmin->assignRole($role);
        }
    }

    public function test_security_headers_are_applied_to_web_responses(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    }

    public function test_custom_error_pages_render_with_ngo_branding(): void
    {
        // 404 Not Found
        $response404 = $this->get('/non-existent-hardening-page-' . uniqid());
        $response404->assertStatus(404);
        $response404->assertSee('Page Not Found');
        $response404->assertSee('Nanban Social Foundation');

        // Branded error views compile cleanly
        $view403 = view('errors.403')->render();
        $this->assertStringContainsString('Access Forbidden', $view403);
        $this->assertStringContainsString('Nanban Social Foundation', $view403);

        $view419 = view('errors.419')->render();
        $this->assertStringContainsString('Page Has Expired', $view419);

        $view429 = view('errors.429')->render();
        $this->assertStringContainsString('Too Many Requests', $view429);

        $view500 = view('errors.500')->render();
        $this->assertStringContainsString('Internal Server Error', $view500);

        $view503 = view('errors.503')->render();
        $this->assertStringContainsString('Platform Under Maintenance', $view503);
    }

    public function test_media_library_full_upload_storage_resolution_and_cleanup_lifecycle(): void
    {
        Storage::fake('public');

        $testFile = UploadedFile::fake()->create('release_test_photo.png', 120, 'image/png');

        $component = Livewire::actingAs($this->superAdmin)
            ->test(MediaLibrary::class)
            ->set('uploadFiles', [$testFile]);

        $component->assertHasNoErrors();

        // Verify DB record
        $media = Media::where('original_name', 'release_test_photo.png')->latest('id')->first();
        $this->assertNotNull($media);
        $this->assertEquals('public', $media->disk);
        $this->assertEquals('image/png', $media->mime_type);

        // Verify physical file was saved on disk
        Storage::disk('public')->assertExists($media->file_path);

        // Verify public URL is generated and valid
        $url = $media->url;
        $this->assertNotEmpty($url);
        $this->assertStringContainsString('/storage/media/', $url);

        // Test deletion through Livewire UI component
        $component->call('deleteMedia', $media->id)
            ->assertHasNoErrors();

        // Verify DB record deleted
        $this->assertDatabaseMissing('media', ['id' => $media->id]);

        // Verify file deleted from disk
        Storage::disk('public')->assertMissing($media->file_path);
    }

    public function test_unsafe_svg_upload_is_blocked_by_security_service(): void
    {
        Storage::fake('public');

        // Create malicious SVG content
        $maliciousSvg = '<?xml version="1.0"?><svg xmlns="http://www.w3.org/2000/svg"><script>alert("xss")</script><rect width="100" height="100"/></svg>';
        $svgFile = UploadedFile::fake()->createWithContent('malicious.svg', $maliciousSvg, 'image/svg+xml');

        $this->expectException(ValidationException::class);
        \App\Services\FileSecurityService::validateAndSanitize($svgFile, 'uploadFiles');
    }

    public function test_public_homepage_contains_zero_developer_branding(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Public branding must be Nanban Social Foundation
        $response->assertSee('Nanban Social Foundation');

        // Developer branding MUST NOT be visible on public HTML
        $content = $response->getContent();
        $this->assertStringNotContainsString('ShanLux NGO Platform', $content);
        $this->assertStringNotContainsString('ShanLux IT Services', $content);
        $this->assertStringNotContainsString('GJ Nexora', $content);
    }

    public function test_unauthenticated_user_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }
}
