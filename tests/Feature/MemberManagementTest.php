<?php

namespace Tests\Feature;

use App\Livewire\Admin\MemberManagement;
use App\Models\Area;
use App\Models\Community;
use App\Models\District;
use App\Models\Member;
use App\Models\User;
use App\Services\MemberCodeService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class MemberManagementTest extends TestCase
{
    protected User $superAdmin;
    protected User $communityLeader;
    protected User $unauthorizedUser;
    protected District $district;
    protected Area $area;
    protected Community $community;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure permissions exist
        $permissions = [
            'members.view',
            'members.create',
            'members.edit',
            'members.delete',
            'members.suspend',
            'members.archive',
            'members.restore',
            'members.export',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Setup Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        $leaderRole = Role::firstOrCreate(['name' => 'Community Leader', 'guard_name' => 'web']);
        $leaderRole->syncPermissions(['members.view', 'members.create']);

        $volunteerRole = Role::firstOrCreate(['name' => 'Volunteer', 'guard_name' => 'web']);
        $volunteerRole->syncPermissions([]);

        // Setup Users
        $this->superAdmin = User::firstOrCreate(
            ['email' => 'member_superadmin@nanbanfoundation.org.in'],
            [
                'name' => 'Member Super Admin',
                'password' => bcrypt('StrongPass123!'),
                'status' => 'active',
            ]
        );
        $this->superAdmin->syncRoles([$superAdminRole]);

        $this->communityLeader = User::firstOrCreate(
            ['email' => 'community_leader@nanbanfoundation.org.in'],
            [
                'name' => 'Community Leader User',
                'password' => bcrypt('StrongPass123!'),
                'status' => 'active',
            ]
        );
        $this->communityLeader->syncRoles([$leaderRole]);

        $this->unauthorizedUser = User::firstOrCreate(
            ['email' => 'unauthorized_volunteer@nanbanfoundation.org.in'],
            [
                'name' => 'Volunteer User',
                'password' => bcrypt('StrongPass123!'),
                'status' => 'active',
            ]
        );
        $this->unauthorizedUser->syncRoles([$volunteerRole]);

        // Setup Location Hierarchy
        $this->district = District::firstOrCreate(
            ['name' => 'Coimbatore'],
            ['code' => 'CBE', 'is_active' => true]
        );

        $this->area = Area::firstOrCreate(
            ['name' => 'Ganapathy', 'district_id' => $this->district->id],
            ['is_active' => true]
        );

        $this->community = Community::firstOrCreate(
            ['name' => 'Ganapathy Upliftment Point'],
            [
                'district_id' => $this->district->id,
                'area_id' => $this->area->id,
                'area' => 'Ganapathy, Coimbatore',
                'description' => 'Grassroots youth and women center.',
                'is_enabled' => true,
            ]
        );
    }

    public function test_member_code_service_generates_standard_format(): void
    {
        $year = (int) date('Y');
        $code1 = MemberCodeService::generate($year);

        $this->assertMatchesRegularExpression('/^NSF-' . $year . '-\d{6}$/', $code1);
    }

    public function test_super_admin_can_view_member_management_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.members.index'));
        $response->assertStatus(200);
        $response->assertSee('Member Management');
    }

    public function test_unauthorized_user_cannot_access_members_directory(): void
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('admin.members.index'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_member_with_location_hierarchy(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(MemberManagement::class)
            ->call('openCreateModal')
            ->set('formData.first_name', 'Muthu')
            ->set('formData.last_name', 'Vel')
            ->set('formData.gender', 'male')
            ->set('formData.phone', '+91 98420 77889')
            ->set('formData.email', 'muthu.vel@example.com')
            ->set('formData.district_id', $this->district->id)
            ->set('formData.area_id', $this->area->id)
            ->set('formData.community_id', $this->community->id)
            ->set('formData.joined_at', '2026-03-15')
            ->set('formData.status', 'active')
            ->set('formData.notes', 'Grassroots agricultural representative')
            ->call('saveMember')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('members', [
            'first_name' => 'Muthu',
            'last_name' => 'Vel',
            'phone' => '+91 98420 77889',
            'email' => 'muthu.vel@example.com',
            'district_id' => $this->district->id,
            'area_id' => $this->area->id,
            'community_id' => $this->community->id,
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'entity_type' => 'members',
        ]);
    }

    public function test_member_validation_enforces_mandatory_fields(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(MemberManagement::class)
            ->call('openCreateModal')
            ->set('formData.first_name', '')
            ->set('formData.joined_at', '')
            ->call('saveMember')
            ->assertHasErrors(['formData.first_name', 'formData.joined_at']);
    }

    public function test_super_admin_can_edit_existing_member(): void
    {
        $member = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Selvam',
            'last_name' => 'R',
            'gender' => 'male',
            'phone' => '+91 98420 11223',
            'email' => 'selvam@example.com',
            'district_id' => $this->district->id,
            'area_id' => $this->area->id,
            'community_id' => $this->community->id,
            'joined_at' => '2026-01-10',
            'status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(MemberManagement::class)
            ->call('openEditModal', $member->id)
            ->assertSet('formData.first_name', 'Selvam')
            ->assertSet('formData.phone', '+91 98420 11223')
            ->set('formData.phone', '+91 98420 99887')
            ->set('formData.notes', 'Updated contact phone number.')
            ->call('saveMember')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'phone' => '+91 98420 99887',
            'notes' => 'Updated contact phone number.',
        ]);
    }

    public function test_super_admin_can_change_member_status_with_audit(): void
    {
        $member = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Anitha',
            'last_name' => 'P',
            'gender' => 'female',
            'joined_at' => '2026-02-01',
            'status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(MemberManagement::class)
            ->call('openStatusModal', $member->id)
            ->set('newStatus', 'suspended')
            ->call('updateStatus')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'status' => 'suspended',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => "Changed status for member '{$member->member_code}' from 'active' to 'suspended'",
            'entity_type' => 'members',
            'entity_id' => $member->id,
        ]);
    }

    public function test_super_admin_can_archive_and_restore_member(): void
    {
        $member = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Kavitha',
            'last_name' => 'M',
            'gender' => 'female',
            'joined_at' => '2026-01-15',
            'status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        $component = Livewire::actingAs($this->superAdmin)
            ->test(MemberManagement::class);

        // Archive
        $component->call('archiveMember', $member->id)
            ->assertHasNoErrors();

        $this->assertSoftDeleted('members', ['id' => $member->id]);
        $this->assertEquals('archived', $member->fresh()->status);

        // Restore
        $component->call('restoreMember', $member->id)
            ->assertHasNoErrors();

        $this->assertNotSoftDeleted('members', ['id' => $member->id]);
        $this->assertEquals('active', $member->fresh()->status);
    }

    public function test_member_search_and_filters(): void
    {
        $m1 = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Dinesh',
            'last_name' => 'Karthik',
            'phone' => '+91 97777 11111',
            'email' => 'dinesh@test.com',
            'district_id' => $this->district->id,
            'area_id' => $this->area->id,
            'community_id' => $this->community->id,
            'joined_at' => '2026-02-10',
            'status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        $m2 = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Pooja',
            'last_name' => 'Sundaram',
            'phone' => '+91 97777 22222',
            'email' => 'pooja@test.com',
            'district_id' => $this->district->id,
            'area_id' => $this->area->id,
            'community_id' => $this->community->id,
            'joined_at' => '2026-02-15',
            'status' => 'pending',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(MemberManagement::class)
            ->set('search', 'Dinesh')
            ->assertSee('Dinesh')
            ->assertDontSee('Pooja')
            ->set('search', '')
            ->set('statusFilter', 'pending')
            ->assertSee('Pooja')
            ->assertDontSee('Dinesh');
    }

    public function test_super_admin_can_upload_profile_photo_for_member(): void
    {
        Storage::fake('public');

        $photoFile = UploadedFile::fake()->create('member_avatar.png', 100, 'image/png');

        $component = Livewire::actingAs($this->superAdmin)
            ->test(MemberManagement::class)
            ->call('openCreateModal')
            ->set('profilePhotoFile', $photoFile)
            ->assertHasNoErrors();

        $this->assertNotEmpty($component->get('formData.profile_photo_id'));
        $this->assertNotEmpty($component->get('formData.profile_photo_url'));

        $this->assertDatabaseHas('media', [
            'original_name' => 'member_avatar.png',
        ]);
    }
}
