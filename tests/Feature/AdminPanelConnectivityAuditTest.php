<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\AuditLog;
use App\Models\Community;
use App\Models\District;
use App\Models\HomepageSection;
use App\Models\LoginHistory;
use App\Models\Media;
use App\Models\Member;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\ThemeSetting;
use App\Models\User;
use App\Models\VolunteerParticipation;
use App\Models\VolunteerProfile;
use App\Services\AuditService;
use App\Services\MemberCodeService;
use App\Services\SuperAdminProtectionService;
use App\Services\VolunteerCodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelConnectivityAuditTest extends TestCase
{
    use RefreshDatabase;

    protected User $superAdmin;
    protected User $regularAdmin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        $this->superAdmin = User::factory()->create([
            'email' => 'superadmin_test@nanban.org',
            'status' => 'active',
        ]);
        $this->superAdmin->assignRole($superAdminRole);

        $this->regularAdmin = User::factory()->create([
            'email' => 'admin_test@nanban.org',
            'status' => 'active',
        ]);
        $this->regularAdmin->assignRole($adminRole);
    }

    /* --------------------------------------------------------------------------
     | 1. Route Connectivity & Middleware Enforcement
     | -------------------------------------------------------------------------- */

    public function test_guests_are_redirected_to_login_for_all_admin_routes(): void
    {
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
            $this->get($route)->assertRedirect(route('login'));
        }
    }

    public function test_inactive_or_suspended_users_are_blocked_from_admin_access(): void
    {
        $inactiveUser = User::factory()->create(['status' => 'inactive']);
        $inactiveUser->assignRole('Super Admin');

        $this->actingAs($inactiveUser)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email']);

        $suspendedUser = User::factory()->create(['status' => 'suspended']);
        $suspendedUser->assignRole('Super Admin');

        $this->actingAs($suspendedUser)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email']);
    }

    public function test_all_admin_routes_render_http_200_for_active_super_admin(): void
    {
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
            $response = $this->actingAs($this->superAdmin)->get($route);
            $response->assertStatus(200);
        }
    }

    /* --------------------------------------------------------------------------
     | 2. User Management & Super Admin Protection
     | -------------------------------------------------------------------------- */

    public function test_user_management_crud_lifecycle(): void
    {
        // Create User via Livewire
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\UserManagement::class)
            ->set('name', 'Kavitha Murugan')
            ->set('email', 'kavitha@example.com')
            ->set('phone', '+91 98400 11223')
            ->set('password', 'Password@1234')
            ->set('password_confirmation', 'Password@1234')
            ->set('status', 'active')
            ->set('selectedRoles', ['Admin'])
            ->call('saveUser')
            ->assertHasNoErrors()
            ->assertSee('Kavitha Murugan');

        $user = User::where('email', 'kavitha@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Admin'));

        // Edit User
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\UserManagement::class)
            ->call('openEditModal', $user->id)
            ->set('name', 'Kavitha M')
            ->set('phone', '+91 98400 99887')
            ->call('saveUser')
            ->assertHasNoErrors();

        $this->assertEquals('Kavitha M', $user->fresh()->name);

        // Toggle Status
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\UserManagement::class)
            ->call('toggleStatus', $user->id);

        $this->assertEquals('inactive', $user->fresh()->status);

        // Delete User
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\UserManagement::class)
            ->call('deleteUser', $user->id);

        $this->assertNull(User::find($user->id));
    }

    public function test_super_admin_protection_prevents_deleting_or_deactivating_sole_super_admin(): void
    {
        // Only 1 super admin active in test ($this->superAdmin)
        $this->assertFalse(SuperAdminProtectionService::canDeactivateUser($this->superAdmin));
        $this->assertFalse(SuperAdminProtectionService::canDeleteUser($this->superAdmin));
        $this->assertFalse(SuperAdminProtectionService::canRemoveSuperAdminRole($this->superAdmin));

        // Attempt deactivation via Livewire
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\UserManagement::class)
            ->call('toggleStatus', $this->superAdmin->id);

        $this->assertEquals('active', $this->superAdmin->fresh()->status);
    }

    /* --------------------------------------------------------------------------
     | 3. Role & Permission Management
     | -------------------------------------------------------------------------- */

    public function test_role_and_permission_matrix_management(): void
    {
        // Create custom role
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\RolePermissionMatrix::class)
            ->set('newRoleName', 'Field Coordinator')
            ->call('createRole')
            ->assertHasNoErrors()
            ->assertSee('Field Coordinator');

        $role = Role::where('name', 'Field Coordinator')->first();
        $this->assertNotNull($role);

        // Toggle permission
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\RolePermissionMatrix::class)
            ->call('selectRole', $role->id)
            ->call('togglePermission', 'members.view')
            ->assertHasNoErrors();

        $this->assertTrue($role->fresh()->hasPermissionTo('members.view'));

        // Revoke permission
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\RolePermissionMatrix::class)
            ->call('selectRole', $role->id)
            ->call('togglePermission', 'members.view')
            ->assertHasNoErrors();

        $this->assertFalse($role->fresh()->hasPermissionTo('members.view'));

        // Delete custom role
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\RolePermissionMatrix::class)
            ->call('deleteRole', $role->id)
            ->assertHasNoErrors();

        $this->assertNull(Role::where('name', 'Field Coordinator')->first());
    }

    /* --------------------------------------------------------------------------
     | 4. Member Management Lifecycle
     | -------------------------------------------------------------------------- */

    public function test_member_management_crud_and_code_generation(): void
    {
        $district = District::firstOrCreate(['name' => 'Coimbatore']);
        $area = Area::firstOrCreate(['district_id' => $district->id, 'name' => 'RS Puram']);
        $community = Community::firstOrCreate([
            'district_id' => $district->id,
            'area_id' => $area->id,
            'name' => 'Gandhipuram Cluster',
            'area' => 'RS Puram',
            'description' => 'Gandhipuram grassroots community unit',
        ]);

        // Register new member
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MemberManagement::class)
            ->call('openCreateModal')
            ->set('formData.first_name', 'Suresh')
            ->set('formData.last_name', 'Kumar')
            ->set('formData.gender', 'male')
            ->set('formData.phone', '+91 98400 55667')
            ->set('formData.email', 'suresh@example.com')
            ->set('formData.district_id', $district->id)
            ->set('formData.area_id', $area->id)
            ->set('formData.community_id', $community->id)
            ->set('formData.joined_at', date('Y-m-d'))
            ->set('formData.status', 'active')
            ->call('saveMember')
            ->assertHasNoErrors();

        $member = Member::where('email', 'suresh@example.com')->first();
        $this->assertNotNull($member);
        $this->assertStringStartsWith('NSF-', $member->member_code);

        // Update Member
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MemberManagement::class)
            ->call('openEditModal', $member->id)
            ->set('formData.first_name', 'Suresh K')
            ->call('saveMember')
            ->assertHasNoErrors();

        $this->assertEquals('Suresh K', $member->fresh()->first_name);

        // Export Members CSV
        $component = Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MemberManagement::class)
            ->call('exportCsv');

        $this->assertNotNull($component);

        // Archive / Soft Delete Member
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MemberManagement::class)
            ->call('archiveMember', $member->id);

        $this->assertTrue($member->fresh()->trashed());

        // Restore Member
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MemberManagement::class)
            ->call('restoreMember', $member->id);

        $this->assertFalse($member->fresh()->trashed());
    }

    /* --------------------------------------------------------------------------
     | 5. Volunteer Management Lifecycle & Workflow
     | -------------------------------------------------------------------------- */

    public function test_volunteer_management_workflow_and_hours_tracking(): void
    {
        $district = District::firstOrCreate(['name' => 'Salem']);
        $area = Area::firstOrCreate(['district_id' => $district->id, 'name' => 'Attur']);
        $community = Community::firstOrCreate([
            'district_id' => $district->id,
            'area_id' => $area->id,
            'name' => 'Town Center',
            'area' => 'Attur',
            'description' => 'Attur town center volunteer cluster',
        ]);

        $member = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Ananya',
            'last_name' => 'Rao',
            'gender' => 'female',
            'phone' => '+91 98400 33445',
            'email' => 'ananya@example.com',
            'district_id' => $district->id,
            'area_id' => $area->id,
            'community_id' => $community->id,
            'status' => 'active',
            'joined_at' => now(),
        ]);

        // Onboard Volunteer Profile
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\VolunteerManagement::class)
            ->call('openCreateModal')
            ->call('selectMember', $member->id)
            ->set('formData.skills', ['Teaching & Tutoring', 'Healthcare & First Aid'])
            ->set('formData.availability', 'weekends')
            ->set('formData.emergency_contact_name', 'Rao Sr')
            ->set('formData.emergency_contact_phone', '+91 98400 00001')
            ->call('saveNewVolunteer')
            ->assertHasNoErrors();

        $volunteer = VolunteerProfile::where('member_id', $member->id)->first();
        $this->assertNotNull($volunteer);
        $this->assertStringStartsWith('VOL-', $volunteer->volunteer_code);
        $this->assertEquals('pending', $volunteer->volunteer_status);

        // Approve Volunteer
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\VolunteerManagement::class)
            ->call('openApprovalModal', $volunteer->id)
            ->call('approveVolunteer')
            ->assertHasNoErrors();

        $this->assertEquals('approved', $volunteer->fresh()->volunteer_status);
        $this->assertNotNull($volunteer->fresh()->approved_at);

        // Add Service Participation Hours
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\VolunteerManagement::class)
            ->call('openAddParticipation', $volunteer->id)
            ->set('activityName', 'Village Science Lab Camp')
            ->set('activityDate', now()->toDateString())
            ->set('activityRole', 'Lead Tutor')
            ->set('activityHours', '6.5')
            ->call('saveParticipation')
            ->assertHasNoErrors();

        $participation = VolunteerParticipation::where('volunteer_profile_id', $volunteer->id)->first();
        $this->assertNotNull($participation);
        $this->assertEquals(6.5, (float) $volunteer->fresh()->total_hours);

        // Export Volunteers CSV
        $export = Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\VolunteerManagement::class)
            ->call('exportCsv');
        $this->assertNotNull($export);
    }

    public function test_volunteer_rejection_requires_mandatory_reason(): void
    {
        $member = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Test',
            'last_name' => 'Applicant',
            'gender' => 'male',
            'phone' => '+91 98400 99000',
            'email' => 'testapp@example.com',
            'status' => 'active',
            'joined_at' => now(),
        ]);

        $volunteer = VolunteerProfile::create([
            'member_id' => $member->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => now(),
            'volunteer_status' => 'pending',
            'availability' => 'flexible',
            'emergency_contact_name' => 'Parent',
            'emergency_contact_phone' => '+91 98400 99001',
        ]);

        // Rejection without reason fails validation
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\VolunteerManagement::class)
            ->call('openRejectionModal', $volunteer->id)
            ->set('rejectionReason', '')
            ->call('rejectVolunteer')
            ->assertHasErrors(['rejectionReason']);

        $this->assertEquals('pending', $volunteer->fresh()->volunteer_status);

        // Rejection with reason succeeds
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\VolunteerManagement::class)
            ->call('openRejectionModal', $volunteer->id)
            ->set('rejectionReason', 'Incomplete contact verification.')
            ->call('rejectVolunteer')
            ->assertHasNoErrors();

        $this->assertEquals('rejected', $volunteer->fresh()->volunteer_status);
        $this->assertEquals('Incomplete contact verification.', $volunteer->fresh()->rejection_reason);
    }

    /* -------------------------------------------------------------
     | 6. Media Library Operations & File Security
     | ------------------------------------------------------------*/

    public function test_media_library_upload_edit_and_deletion(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('test_banner.jpg', 200, 'image/jpeg');

        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MediaLibrary::class)
            ->set('uploadFiles', [$file])
            ->assertHasNoErrors();

        $media = Media::where('original_name', 'test_banner.jpg')->first();
        $this->assertNotNull($media);
        Storage::disk('public')->assertExists($media->file_path);

        // Edit Metadata
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MediaLibrary::class)
            ->call('openEdit', $media->id)
            ->set('editTitle', 'Homepage Hero Banner')
            ->set('editAltText', 'Rural education students')
            ->call('saveEdit')
            ->assertHasNoErrors();

        $this->assertEquals('Homepage Hero Banner', $media->fresh()->title);

        // Delete Media
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\MediaLibrary::class)
            ->call('deleteMedia', $media->id)
            ->assertHasNoErrors();

        $this->assertNull(Media::find($media->id));
        Storage::disk('public')->assertMissing($media->file_path);
    }

    /* --------------------------------------------------------------------------
     | 7. Homepage CMS, Theme & Website Settings
     | -------------------------------------------------------------------------- */

    public function test_homepage_cms_section_toggling_and_editing(): void
    {
        $section = HomepageSection::firstOrCreate(
            ['section_key' => 'hero'],
            ['title' => 'Hero Banner', 'is_enabled' => true, 'order_position' => 1]
        );

        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\HomepageEditor::class)
            ->call('toggleSection', $section->id);

        $this->assertFalse((bool) $section->fresh()->is_enabled);

        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\HomepageEditor::class)
            ->call('toggleSection', $section->id);

        $this->assertTrue((bool) $section->fresh()->is_enabled);
    }

    public function test_website_settings_and_top_bar_configuration(): void
    {
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\WebsiteSettings::class)
            ->set('topBarEnabled', true)
            ->set('topBarText', 'Verified 80G Certified Non-Profit')
            ->set('topBarBadge', 'OFFICIAL')
            ->call('saveTopBar')
            ->assertHasNoErrors();

        $this->assertEquals('Verified 80G Certified Non-Profit', SiteSetting::get('top_bar_text'));
        $this->assertEquals('OFFICIAL', SiteSetting::get('top_bar_badge'));
    }

    /* --------------------------------------------------------------------------
     | 8. Audit Logging & Login History Verification
     | -------------------------------------------------------------------------- */

    public function test_administrative_actions_record_audit_logs(): void
    {
        AuditService::log(
            'Test Audit Event Execution',
            'system_audit',
            1,
            ['old' => 'val1'],
            ['new' => 'val2']
        );

        $log = AuditLog::where('action', 'Test Audit Event Execution')->first();
        $this->assertNotNull($log);
        $this->assertEquals('system_audit', $log->entity_type);

        // Verify Audit Log Viewer renders
        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\AuditLogViewer::class)
            ->assertSee('Test Audit Event Execution');
    }

    public function test_login_history_records_and_renders(): void
    {
        LoginHistory::create([
            'user_id' => $this->superAdmin->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit Browser',
            'status' => 'successful',
            'login_at' => now(),
        ]);

        Livewire::actingAs($this->superAdmin, 'web')
            ->test(\App\Livewire\Admin\LoginHistoryViewer::class)
            ->assertSee('127.0.0.1')
            ->assertSee('Success');
    }
}
