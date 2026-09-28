<?php

namespace Tests\Feature;

use App\Livewire\Admin\VolunteerManagement;
use App\Models\Area;
use App\Models\Community;
use App\Models\District;
use App\Models\Member;
use App\Models\User;
use App\Models\VolunteerParticipation;
use App\Models\VolunteerProfile;
use App\Services\MemberCodeService;
use App\Services\VolunteerCodeService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class VolunteerManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected User $superAdmin;
    protected User $communityLeaderA;
    protected User $communityLeaderB;
    protected User $unauthorizedUser;
    protected District $district;
    protected Area $areaA;
    protected Area $areaB;
    protected Community $communityA;
    protected Community $communityB;
    protected Member $memberA;
    protected Member $memberB;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure permissions exist
        $permissions = [
            'members.view',
            'members.create',
            'members.edit',
            'volunteers.view',
            'volunteers.create',
            'volunteers.edit',
            'volunteers.delete',
            'volunteers.approve',
            'volunteers.reject',
            'volunteers.suspend',
            'volunteers.archive',
            'volunteers.restore',
            'volunteers.export',
            'volunteers.participation.view',
            'volunteers.participation.create',
            'volunteers.participation.edit',
            'volunteers.participation.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Setup Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        $leaderRole = Role::firstOrCreate(['name' => 'Community Leader', 'guard_name' => 'web']);
        $leaderRole->syncPermissions([
            'members.view',
            'volunteers.view',
            'volunteers.create',
            'volunteers.edit',
            'volunteers.participation.view',
            'volunteers.participation.create',
        ]);

        $volunteerRole = Role::firstOrCreate(['name' => 'Volunteer', 'guard_name' => 'web']);
        $volunteerRole->syncPermissions([]);

        // Setup Hierarchy
        $this->district = District::firstOrCreate(
            ['name' => 'Coimbatore'],
            ['code' => 'CBE', 'is_active' => true]
        );

        $this->areaA = Area::firstOrCreate(
            ['name' => 'Ganapathy', 'district_id' => $this->district->id],
            ['is_active' => true]
        );

        $this->areaB = Area::firstOrCreate(
            ['name' => 'Peelamedu', 'district_id' => $this->district->id],
            ['is_active' => true]
        );

        $this->communityA = Community::firstOrCreate(
            ['name' => 'Ganapathy Upliftment Point'],
            [
                'district_id' => $this->district->id,
                'area_id' => $this->areaA->id,
                'area' => 'Ganapathy, Coimbatore',
                'description' => 'Grassroots youth center A.',
                'is_enabled' => true,
            ]
        );

        $this->communityB = Community::firstOrCreate(
            ['name' => 'Peelamedu Literacy Point'],
            [
                'district_id' => $this->district->id,
                'area_id' => $this->areaB->id,
                'area' => 'Peelamedu, Coimbatore',
                'description' => 'Grassroots youth center B.',
                'is_enabled' => true,
            ]
        );

        // Setup Users
        $this->superAdmin = User::firstOrCreate(
            ['email' => 'vol_superadmin@nanbanfoundation.org.in'],
            [
                'name' => 'Volunteer Super Admin',
                'password' => bcrypt('StrongPass123!'),
                'status' => 'active',
            ]
        );
        $this->superAdmin->syncRoles([$superAdminRole]);

        $this->communityLeaderA = User::firstOrCreate(
            ['email' => 'leader_a@nanbanfoundation.org.in'],
            [
                'name' => 'Community Leader A',
                'password' => bcrypt('StrongPass123!'),
                'status' => 'active',
                'community_id' => $this->communityA->id,
            ]
        );
        $this->communityLeaderA->syncRoles([$leaderRole]);

        $this->communityLeaderB = User::firstOrCreate(
            ['email' => 'leader_b@nanbanfoundation.org.in'],
            [
                'name' => 'Community Leader B',
                'password' => bcrypt('StrongPass123!'),
                'status' => 'active',
                'community_id' => $this->communityB->id,
            ]
        );
        $this->communityLeaderB->syncRoles([$leaderRole]);

        $this->unauthorizedUser = User::firstOrCreate(
            ['email' => 'vol_unauthorized@nanbanfoundation.org.in'],
            [
                'name' => 'Unauthorized Volunteer User',
                'password' => bcrypt('StrongPass123!'),
                'status' => 'active',
            ]
        );
        $this->unauthorizedUser->syncRoles([$volunteerRole]);

        // Clean any existing test members
        Member::whereIn('email', ['member.test.a@example.com', 'member.test.b@example.com'])->forceDelete();

        // Setup fresh Members
        $this->memberA = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Karthik',
            'last_name' => 'Raja',
            'gender' => 'male',
            'email' => 'member.test.a@example.com',
            'phone' => '+91 98401 11111',
            'district_id' => $this->district->id,
            'area_id' => $this->areaA->id,
            'community_id' => $this->communityA->id,
            'joined_at' => '2026-01-15',
            'status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        $this->memberB = Member::create([
            'member_code' => MemberCodeService::generate(),
            'first_name' => 'Deepa',
            'last_name' => 'Sundaram',
            'gender' => 'female',
            'email' => 'member.test.b@example.com',
            'phone' => '+91 98402 22222',
            'district_id' => $this->district->id,
            'area_id' => $this->areaB->id,
            'community_id' => $this->communityB->id,
            'joined_at' => '2026-02-10',
            'status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);
    }

    public function test_volunteer_code_service_generates_standard_concurrency_safe_format(): void
    {
        $year = (int) date('Y');
        $code = VolunteerCodeService::generate($year);

        $this->assertMatchesRegularExpression('/^VOL-' . $year . '-\d{6}$/', $code);
    }

    public function test_super_admin_can_view_volunteer_management_page(): void
    {
        $response = $this->actingAs($this->superAdmin)->get(route('admin.volunteers'));
        $response->assertStatus(200);
        $response->assertSee('Volunteer Management');
    }

    public function test_unauthorized_user_cannot_access_volunteer_directory(): void
    {
        $response = $this->actingAs($this->unauthorizedUser)->get(route('admin.volunteers'));
        $response->assertStatus(403);
    }

    public function test_super_admin_can_onboard_member_as_volunteer(): void
    {
        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openCreateModal')
            ->call('selectMember', $this->memberA->id)
            ->set('formData.application_date', '2026-03-01')
            ->set('formData.availability', 'weekends')
            ->set('formData.availability_notes', 'Available Saturday afternoons')
            ->set('formData.skills', ['Teaching & Tutoring', 'IT & Technical Support'])
            ->set('formData.interests', ['Education & Literacy', 'Rural Development'])
            ->set('formData.emergency_contact_name', 'Sundar Raja')
            ->set('formData.emergency_contact_phone', '+91 94440 99887')
            ->set('formData.emergency_contact_relation', 'Father')
            ->set('formData.notes', 'Eager to mentor youth in computer literacy.')
            ->call('saveNewVolunteer')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('volunteer_profiles', [
            'member_id' => $this->memberA->id,
            'volunteer_status' => 'pending',
            'availability' => 'weekends',
            'emergency_contact_name' => 'Sundar Raja',
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'entity_type' => 'volunteer_profiles',
        ]);
    }

    public function test_cannot_create_duplicate_volunteer_profile_for_same_member(): void
    {
        VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'pending',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openCreateModal')
            ->call('selectMember', $this->memberA->id)
            ->call('saveNewVolunteer')
            ->assertHasErrors(['selectedMemberId']);
    }

    public function test_super_admin_can_approve_pending_volunteer(): void
    {
        $volunteer = VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'pending',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openApprovalModal', $volunteer->id)
            ->call('approveVolunteer')
            ->assertHasNoErrors();

        $volunteer->refresh();
        $this->assertEquals('approved', $volunteer->volunteer_status);
        $this->assertNotNull($volunteer->approved_at);
        $this->assertEquals($this->superAdmin->id, $volunteer->approved_by);

        $this->assertDatabaseHas('audit_logs', [
            'entity_type' => 'volunteer_profiles',
            'entity_id' => $volunteer->id,
        ]);
    }

    public function test_super_admin_can_reject_volunteer_with_mandatory_reason(): void
    {
        $volunteer = VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'pending',
            'created_by' => $this->superAdmin->id,
        ]);

        // Attempt rejection without reason fails
        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openRejectionModal', $volunteer->id)
            ->set('rejectionReason', '')
            ->call('rejectVolunteer')
            ->assertHasErrors(['rejectionReason']);

        // Rejection with reason succeeds
        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openRejectionModal', $volunteer->id)
            ->set('rejectionReason', 'Applicant relocated outside foundation district.')
            ->call('rejectVolunteer')
            ->assertHasNoErrors();

        $volunteer->refresh();
        $this->assertEquals('rejected', $volunteer->volunteer_status);
        $this->assertNotNull($volunteer->rejected_at);
        $this->assertEquals($this->superAdmin->id, $volunteer->rejected_by);
        $this->assertEquals('Applicant relocated outside foundation district.', $volunteer->rejection_reason);
    }

    public function test_super_admin_can_change_volunteer_status(): void
    {
        $volunteer = VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'approved',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openStatusModal', $volunteer->id)
            ->set('newStatus', 'active')
            ->call('updateStatus')
            ->assertHasNoErrors();

        $volunteer->refresh();
        $this->assertEquals('active', $volunteer->volunteer_status);
    }

    public function test_super_admin_can_edit_volunteer_profile(): void
    {
        $volunteer = VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'active',
            'availability' => 'weekdays',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openEditModal', $volunteer->id)
            ->set('formData.availability', 'flexible')
            ->set('formData.availability_notes', 'Available 24/7 for disaster response')
            ->call('updateVolunteer')
            ->assertHasNoErrors();

        $volunteer->refresh();
        $this->assertEquals('flexible', $volunteer->availability);
        $this->assertEquals('Available 24/7 for disaster response', $volunteer->availability_notes);
    }

    public function test_super_admin_can_log_volunteer_participation_and_aggregate_hours(): void
    {
        $volunteer = VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('openParticipationModal', $volunteer->id)
            ->set('activityName', 'Tree Plantation Drive')
            ->set('activityDate', '2026-03-10')
            ->set('activityRole', 'Coordinator')
            ->set('activityHours', '4.5')
            ->set('activityNotes', 'Led 15 volunteers in planting 200 saplings.')
            ->call('saveParticipation')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('volunteer_participations', [
            'volunteer_profile_id' => $volunteer->id,
            'activity_name' => 'Tree Plantation Drive',
            'hours' => 4.5,
        ]);

        // Add a second participation record
        VolunteerParticipation::create([
            'volunteer_profile_id' => $volunteer->id,
            'activity_name' => 'Medical Camp Support',
            'activity_date' => '2026-03-15',
            'role' => 'Registration Assistant',
            'hours' => 3.5,
            'created_by' => $this->superAdmin->id,
        ]);

        $volunteer->refresh();
        $this->assertEquals(8.0, $volunteer->total_hours);
    }

    public function test_super_admin_can_soft_delete_and_restore_volunteer(): void
    {
        $volunteer = VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('deleteVolunteer', $volunteer->id);

        $this->assertSoftDeleted('volunteer_profiles', ['id' => $volunteer->id]);

        Livewire::actingAs($this->superAdmin)
            ->test(VolunteerManagement::class)
            ->call('restoreVolunteer', $volunteer->id);

        $this->assertNotSoftDeleted('volunteer_profiles', ['id' => $volunteer->id]);
    }

    public function test_super_admin_can_export_volunteers_csv(): void
    {
        VolunteerProfile::create([
            'member_id' => $this->memberA->id,
            'volunteer_code' => VolunteerCodeService::generate(),
            'application_date' => '2026-03-01',
            'volunteer_status' => 'active',
            'created_by' => $this->superAdmin->id,
        ]);

        $this->actingAs($this->superAdmin);
        $component = new VolunteerManagement();
        $response = $component->exportCsv();
        $this->assertInstanceOf(\Symfony\Component\HttpFoundation\StreamedResponse::class, $response);
    }
}
