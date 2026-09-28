<?php

namespace App\Livewire\Admin;

use App\Models\Area;
use App\Models\Community;
use App\Models\District;
use App\Models\Member;
use App\Models\VolunteerParticipation;
use App\Models\VolunteerProfile;
use App\Services\AuditService;
use App\Services\VolunteerCodeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
class VolunteerManagement extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $statusFilter = 'all';

    #[Url(history: true)]
    public string $districtFilter = '';

    #[Url(history: true)]
    public string $areaFilter = '';

    #[Url(history: true)]
    public string $communityFilter = '';

    #[Url(history: true)]
    public string $skillFilter = 'all';

    #[Url(history: true)]
    public string $availabilityFilter = 'all';

    // Modals
    public bool $showCreateModal = false;
    public bool $showEditModal = false;
    public ?int $selectedVolunteerId = null;

    public bool $showViewModal = false;
    public ?VolunteerProfile $viewVolunteer = null;
    public array $viewVolunteerAuditLogs = [];

    public bool $showApprovalModal = false;
    public ?int $approvalVolunteerId = null;
    public string $approvalVolunteerCode = '';
    public string $approvalMemberName = '';

    public bool $showRejectionModal = false;
    public ?int $rejectionVolunteerId = null;
    public string $rejectionVolunteerCode = '';
    public string $rejectionMemberName = '';
    public string $rejectionReason = '';

    public bool $showStatusModal = false;
    public ?int $statusVolunteerId = null;
    public string $statusVolunteerCode = '';
    public string $statusMemberName = '';
    public string $oldStatus = '';
    public string $newStatus = 'active';

    public bool $showParticipationModal = false;
    public ?int $participationVolunteerId = null;
    public ?int $editingParticipationId = null;
    public string $activityName = '';
    public string $activityDate = '';
    public string $activityRole = 'Volunteer';
    public string $activityHours = '4.0';
    public string $activityNotes = '';

    // Member Selection during Onboarding
    public string $memberSearch = '';
    public ?int $selectedMemberId = null;
    public ?Member $selectedMember = null;

    // Available Skills & Interests Catalogs
    public array $availableSkills = [
        'Teaching & Tutoring',
        'Healthcare & First Aid',
        'Community Outreach',
        'Event Coordination',
        'Social Media & PR',
        'Photography & Media',
        'IT & Technical Support',
        'Fundraising & Grants',
        'Translation & Writing',
        'Environmental Care',
        'Elderly & Child Care',
        'Disaster Relief',
    ];

    public array $availableInterests = [
        'Education & Literacy',
        'Preventive Healthcare',
        'Women Empowerment',
        'Rural Development',
        'Environmental Sustainability',
        'Child Welfare',
        'Elderly Support',
        'Food & Nutrition Distribution',
        'Youth Skill Building',
        'Disaster Response',
    ];

    // Form Data
    public array $formData = [
        'availability' => 'both',
        'availability_notes' => '',
        'skills' => [],
        'interests' => [],
        'preferred_programs' => [],
        'emergency_contact_name' => '',
        'emergency_contact_phone' => '',
        'emergency_contact_relation' => '',
        'notes' => '',
        'application_date' => '',
        'volunteer_status' => 'pending',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updatingDistrictFilter(): void
    {
        $this->areaFilter = '';
        $this->communityFilter = '';
        $this->resetPage();
    }

    public function updatingAreaFilter(): void
    {
        $this->communityFilter = '';
        $this->resetPage();
    }

    public function updatingCommunityFilter(): void
    {
        $this->resetPage();
    }

    public function updatingSkillFilter(): void
    {
        $this->resetPage();
    }

    public function updatingAvailabilityFilter(): void
    {
        $this->resetPage();
    }

    /* -------------------------------------------------------------
     | Onboarding & Create Volunteer Flow
     | ------------------------------------------------------------*/

    public function openCreateModal(): void
    {
        Gate::authorize('create', VolunteerProfile::class);

        $this->resetValidation();
        $this->selectedMemberId = null;
        $this->selectedMember = null;
        $this->memberSearch = '';

        $this->formData = [
            'availability' => 'both',
            'availability_notes' => '',
            'skills' => ['Community Outreach'],
            'interests' => ['Education & Literacy'],
            'preferred_programs' => [],
            'emergency_contact_name' => '',
            'emergency_contact_phone' => '',
            'emergency_contact_relation' => '',
            'notes' => '',
            'application_date' => date('Y-m-d'),
            'volunteer_status' => 'pending',
        ];

        $this->showCreateModal = true;
    }

    public function selectMember(int $memberId): void
    {
        $member = Member::forUserScope()->findOrFail($memberId);

        if ($member->volunteerProfile()->exists()) {
            session()->flash('error', "Member '{$member->full_name}' ({$member->member_code}) already has a volunteer profile.");
            return;
        }

        $this->selectedMemberId = $member->id;
        $this->selectedMember = $member;
    }

    public function toggleSkill(string $skill): void
    {
        if (in_array($skill, $this->formData['skills'], true)) {
            $this->formData['skills'] = array_values(array_diff($this->formData['skills'], [$skill]));
        } else {
            $this->formData['skills'][] = $skill;
        }
    }

    public function toggleInterest(string $interest): void
    {
        if (in_array($interest, $this->formData['interests'], true)) {
            $this->formData['interests'] = array_values(array_diff($this->formData['interests'], [$interest]));
        } else {
            $this->formData['interests'][] = $interest;
        }
    }

    public function saveNewVolunteer(): void
    {
        Gate::authorize('create', VolunteerProfile::class);

        if (!$this->selectedMemberId) {
            $this->addError('selectedMemberId', 'Please search and select an eligible grassroots member.');
            return;
        }

        $member = Member::forUserScope()->findOrFail($this->selectedMemberId);

        if ($member->volunteerProfile()->exists()) {
            $this->addError('selectedMemberId', "Member '{$member->full_name}' already has a volunteer profile.");
            return;
        }

        $this->validate([
            'formData.application_date' => 'required|date',
            'formData.availability' => 'required|in:weekdays,weekends,both,flexible',
            'formData.availability_notes' => 'nullable|string|max:500',
            'formData.skills' => 'nullable|array',
            'formData.interests' => 'nullable|array',
            'formData.emergency_contact_name' => 'nullable|string|max:100',
            'formData.emergency_contact_phone' => 'nullable|string|max:30',
            'formData.emergency_contact_relation' => 'nullable|string|max:50',
            'formData.notes' => 'nullable|string|max:1000',
            'formData.volunteer_status' => 'required|in:pending,approved,active,inactive',
        ]);

        $volunteerCode = VolunteerCodeService::generate();

        $payload = [
            'member_id' => $member->id,
            'volunteer_code' => $volunteerCode,
            'application_date' => $this->formData['application_date'],
            'volunteer_status' => $this->formData['volunteer_status'],
            'availability' => $this->formData['availability'],
            'availability_notes' => !empty($this->formData['availability_notes']) ? trim($this->formData['availability_notes']) : null,
            'skills' => $this->formData['skills'],
            'interests' => $this->formData['interests'],
            'preferred_programs' => $this->formData['preferred_programs'],
            'emergency_contact_name' => !empty($this->formData['emergency_contact_name']) ? trim($this->formData['emergency_contact_name']) : null,
            'emergency_contact_phone' => !empty($this->formData['emergency_contact_phone']) ? trim($this->formData['emergency_contact_phone']) : null,
            'emergency_contact_relation' => !empty($this->formData['emergency_contact_relation']) ? trim($this->formData['emergency_contact_relation']) : null,
            'notes' => !empty($this->formData['notes']) ? trim($this->formData['notes']) : null,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ];

        if ($this->formData['volunteer_status'] === 'approved' || $this->formData['volunteer_status'] === 'active') {
            $payload['approved_at'] = now();
            $payload['approved_by'] = Auth::id();
        }

        $volunteer = VolunteerProfile::create($payload);

        AuditService::log(
            "Registered volunteer profile '{$volunteer->volunteer_code}' for member '{$member->full_name}' ({$member->member_code})",
            'volunteer_profiles',
            $volunteer->id,
            null,
            $payload
        );

        $this->showCreateModal = false;
        $this->selectedMemberId = null;
        $this->selectedMember = null;
        session()->flash('success', "Volunteer profile '{$volunteer->volunteer_code}' created successfully.");
    }

    /* -------------------------------------------------------------
     | Edit Volunteer
     | ------------------------------------------------------------*/

    public function openEditModal(int $id): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($id);
        Gate::authorize('update', $volunteer);

        $this->selectedVolunteerId = $volunteer->id;
        $this->resetValidation();

        $this->formData = [
            'availability' => $volunteer->availability ?? 'both',
            'availability_notes' => $volunteer->availability_notes ?? '',
            'skills' => $volunteer->skills ?? [],
            'interests' => $volunteer->interests ?? [],
            'preferred_programs' => $volunteer->preferred_programs ?? [],
            'emergency_contact_name' => $volunteer->emergency_contact_name ?? '',
            'emergency_contact_phone' => $volunteer->emergency_contact_phone ?? '',
            'emergency_contact_relation' => $volunteer->emergency_contact_relation ?? '',
            'notes' => $volunteer->notes ?? '',
            'application_date' => $volunteer->application_date ? $volunteer->application_date->format('Y-m-d') : date('Y-m-d'),
            'volunteer_status' => $volunteer->volunteer_status,
        ];

        $this->showEditModal = true;
    }

    public function updateVolunteer(): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($this->selectedVolunteerId);
        Gate::authorize('update', $volunteer);

        $this->validate([
            'formData.availability' => 'required|in:weekdays,weekends,both,flexible',
            'formData.availability_notes' => 'nullable|string|max:500',
            'formData.skills' => 'nullable|array',
            'formData.interests' => 'nullable|array',
            'formData.emergency_contact_name' => 'nullable|string|max:100',
            'formData.emergency_contact_phone' => 'nullable|string|max:30',
            'formData.emergency_contact_relation' => 'nullable|string|max:50',
            'formData.notes' => 'nullable|string|max:1000',
        ]);

        $payload = [
            'availability' => $this->formData['availability'],
            'availability_notes' => !empty($this->formData['availability_notes']) ? trim($this->formData['availability_notes']) : null,
            'skills' => $this->formData['skills'],
            'interests' => $this->formData['interests'],
            'emergency_contact_name' => !empty($this->formData['emergency_contact_name']) ? trim($this->formData['emergency_contact_name']) : null,
            'emergency_contact_phone' => !empty($this->formData['emergency_contact_phone']) ? trim($this->formData['emergency_contact_phone']) : null,
            'emergency_contact_relation' => !empty($this->formData['emergency_contact_relation']) ? trim($this->formData['emergency_contact_relation']) : null,
            'notes' => !empty($this->formData['notes']) ? trim($this->formData['notes']) : null,
            'updated_by' => Auth::id(),
        ];

        $oldValues = $volunteer->only(array_keys($payload));
        $volunteer->update($payload);

        AuditService::log(
            "Updated volunteer profile '{$volunteer->volunteer_code}' for member '{$volunteer->member?->full_name}'",
            'volunteer_profiles',
            $volunteer->id,
            $oldValues,
            $payload
        );

        $this->showEditModal = false;
        $this->selectedVolunteerId = null;
        session()->flash('success', "Volunteer profile '{$volunteer->volunteer_code}' updated successfully.");
    }

    /* -------------------------------------------------------------
     | View Volunteer Profile
     | ------------------------------------------------------------*/

    public function openViewModal(int $id): void
    {
        $this->viewVolunteer = VolunteerProfile::withTrashed()
            ->with([
                'member.district',
                'member.area',
                'member.community',
                'member.profilePhoto',
                'participations' => fn($q) => $q->orderBy('activity_date', 'desc'),
                'approver',
                'rejecter',
                'creator',
                'updater',
            ])
            ->findOrFail($id);

        Gate::authorize('view', $this->viewVolunteer);

        $this->viewVolunteerAuditLogs = \App\Models\AuditLog::where('entity_type', 'volunteer_profiles')
            ->where('entity_id', $this->viewVolunteer->id)
            ->with('user')
            ->latest('id')
            ->take(10)
            ->get()
            ->toArray();

        $this->showViewModal = true;
    }

    /* -------------------------------------------------------------
     | Approval & Rejection Workflows
     | ------------------------------------------------------------*/

    public function openApprovalModal(int $id): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($id);
        Gate::authorize('approve', $volunteer);

        $this->approvalVolunteerId = $volunteer->id;
        $this->approvalVolunteerCode = $volunteer->volunteer_code;
        $this->approvalMemberName = $volunteer->member?->full_name ?? 'Volunteer';
        $this->showApprovalModal = true;
    }

    public function approveVolunteer(): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($this->approvalVolunteerId);
        Gate::authorize('approve', $volunteer);

        $oldStatus = $volunteer->volunteer_status;
        $volunteer->volunteer_status = 'approved';
        $volunteer->approved_at = now();
        $volunteer->approved_by = Auth::id();
        $volunteer->rejected_at = null;
        $volunteer->rejected_by = null;
        $volunteer->rejection_reason = null;
        $volunteer->updated_by = Auth::id();
        $volunteer->save();

        AuditService::log(
            "Approved volunteer application '{$volunteer->volunteer_code}' for member '{$volunteer->member?->full_name}'",
            'volunteer_profiles',
            $volunteer->id,
            ['volunteer_status' => $oldStatus],
            ['volunteer_status' => 'approved', 'approved_at' => now()]
        );

        $this->showApprovalModal = false;
        if ($this->showViewModal && $this->viewVolunteer?->id === $volunteer->id) {
            $this->openViewModal($volunteer->id);
        }
        session()->flash('success', "Volunteer application '{$volunteer->volunteer_code}' has been approved.");
    }

    public function openRejectionModal(int $id): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($id);
        Gate::authorize('reject', $volunteer);

        $this->rejectionVolunteerId = $volunteer->id;
        $this->rejectionVolunteerCode = $volunteer->volunteer_code;
        $this->rejectionMemberName = $volunteer->member?->full_name ?? 'Volunteer';
        $this->rejectionReason = '';
        $this->showRejectionModal = true;
    }

    public function rejectVolunteer(): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($this->rejectionVolunteerId);
        Gate::authorize('reject', $volunteer);

        $this->validate([
            'rejectionReason' => 'required|string|min:5|max:500',
        ], [
            'rejectionReason.required' => 'Please provide a clear reason for rejecting the volunteer application.',
        ]);

        $oldStatus = $volunteer->volunteer_status;
        $volunteer->volunteer_status = 'rejected';
        $volunteer->rejected_at = now();
        $volunteer->rejected_by = Auth::id();
        $volunteer->rejection_reason = trim($this->rejectionReason);
        $volunteer->updated_by = Auth::id();
        $volunteer->save();

        AuditService::log(
            "Rejected volunteer application '{$volunteer->volunteer_code}' with reason: '{$this->rejectionReason}'",
            'volunteer_profiles',
            $volunteer->id,
            ['volunteer_status' => $oldStatus],
            ['volunteer_status' => 'rejected', 'rejection_reason' => $this->rejectionReason]
        );

        $this->showRejectionModal = false;
        if ($this->showViewModal && $this->viewVolunteer?->id === $volunteer->id) {
            $this->openViewModal($volunteer->id);
        }
        session()->flash('success', "Volunteer application '{$volunteer->volunteer_code}' has been marked as rejected.");
    }

    /* -------------------------------------------------------------
     | Status Transitions
     | ------------------------------------------------------------*/

    public function openStatusModal(int $id): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($id);
        Gate::authorize('changeStatus', $volunteer);

        $this->statusVolunteerId = $volunteer->id;
        $this->statusVolunteerCode = $volunteer->volunteer_code;
        $this->statusMemberName = $volunteer->member?->full_name ?? 'Volunteer';
        $this->oldStatus = $volunteer->volunteer_status;
        $this->newStatus = $volunteer->volunteer_status;
        $this->showStatusModal = true;
    }

    public function updateStatus(): void
    {
        $volunteer = VolunteerProfile::withTrashed()->with('member')->findOrFail($this->statusVolunteerId);
        Gate::authorize('changeStatus', $volunteer);

        $this->validate([
            'newStatus' => 'required|in:pending,approved,active,inactive,suspended,archived',
        ]);

        $oldStatus = $volunteer->volunteer_status;
        $volunteer->volunteer_status = $this->newStatus;
        $volunteer->updated_by = Auth::id();
        $volunteer->save();

        AuditService::log(
            "Changed status for volunteer '{$volunteer->volunteer_code}' from '{$oldStatus}' to '{$this->newStatus}'",
            'volunteer_profiles',
            $volunteer->id,
            ['volunteer_status' => $oldStatus],
            ['volunteer_status' => $this->newStatus]
        );

        $this->showStatusModal = false;
        if ($this->showViewModal && $this->viewVolunteer?->id === $volunteer->id) {
            $this->openViewModal($volunteer->id);
        }
        session()->flash('success', "Volunteer '{$volunteer->volunteer_code}' status updated to " . ucfirst($this->newStatus) . '.');
    }

    /* -------------------------------------------------------------
     | Archive & Restore
     | ------------------------------------------------------------*/

    public function archiveVolunteer(int $id): void
    {
        $volunteer = VolunteerProfile::findOrFail($id);
        Gate::authorize('delete', $volunteer);

        $volunteer->volunteer_status = 'archived';
        $volunteer->updated_by = Auth::id();
        $volunteer->save();
        $volunteer->delete(); // Soft delete

        AuditService::log(
            "Archived volunteer profile '{$volunteer->volunteer_code}' ({$volunteer->member?->full_name})",
            'volunteer_profiles',
            $volunteer->id,
            ['volunteer_status' => 'active'],
            ['volunteer_status' => 'archived', 'deleted_at' => now()]
        );

        session()->flash('success', "Volunteer profile '{$volunteer->volunteer_code}' has been archived.");
    }

    public function deleteVolunteer(int $id): void
    {
        $this->archiveVolunteer($id);
    }

    public function restoreVolunteer(int $id): void
    {
        $volunteer = VolunteerProfile::onlyTrashed()->with('member')->findOrFail($id);
        Gate::authorize('restore', $volunteer);

        $volunteer->restore();
        $volunteer->volunteer_status = 'active';
        $volunteer->updated_by = Auth::id();
        $volunteer->save();

        AuditService::log(
            "Restored archived volunteer profile '{$volunteer->volunteer_code}' ({$volunteer->member?->full_name})",
            'volunteer_profiles',
            $volunteer->id,
            ['volunteer_status' => 'archived'],
            ['volunteer_status' => 'active']
        );

        session()->flash('success', "Volunteer profile '{$volunteer->volunteer_code}' restored to Active status.");
    }

    /* -------------------------------------------------------------
     | Participation Logging
     | ------------------------------------------------------------*/

    public function openAddParticipation(int $volunteerId): void
    {
        $volunteer = VolunteerProfile::withTrashed()->findOrFail($volunteerId);
        Gate::authorize('addParticipation', $volunteer);

        $this->participationVolunteerId = $volunteer->id;
        $this->editingParticipationId = null;
        $this->activityName = '';
        $this->activityDate = date('Y-m-d');
        $this->activityRole = 'Volunteer Support';
        $this->activityHours = '4.0';
        $this->activityNotes = '';
        $this->showParticipationModal = true;
    }

    public function openParticipationModal(int $volunteerId): void
    {
        $this->openAddParticipation($volunteerId);
    }

    public function openEditParticipation(int $participationId): void
    {
        $participation = VolunteerParticipation::with('volunteerProfile')->findOrFail($participationId);
        Gate::authorize('editParticipation', $participation->volunteerProfile);

        $this->participationVolunteerId = $participation->volunteer_profile_id;
        $this->editingParticipationId = $participation->id;
        $this->activityName = $participation->activity_name;
        $this->activityDate = $participation->activity_date->format('Y-m-d');
        $this->activityRole = $participation->role ?? 'Volunteer Support';
        $this->activityHours = (string) $participation->hours;
        $this->activityNotes = $participation->notes ?? '';
        $this->showParticipationModal = true;
    }

    public function saveParticipation(): void
    {
        $volunteer = VolunteerProfile::withTrashed()->findOrFail($this->participationVolunteerId);

        if ($this->editingParticipationId) {
            Gate::authorize('editParticipation', $volunteer);
        } else {
            Gate::authorize('addParticipation', $volunteer);
        }

        $this->validate([
            'activityName' => 'required|string|max:255',
            'activityDate' => 'required|date|before_or_equal:today',
            'activityRole' => 'nullable|string|max:100',
            'activityHours' => 'required|numeric|min:0.25|max:24',
            'activityNotes' => 'nullable|string|max:500',
        ]);

        $payload = [
            'volunteer_profile_id' => $volunteer->id,
            'activity_name' => trim($this->activityName),
            'activity_date' => $this->activityDate,
            'role' => trim($this->activityRole),
            'hours' => (float) $this->activityHours,
            'notes' => !empty($this->activityNotes) ? trim($this->activityNotes) : null,
            'updated_by' => Auth::id(),
        ];

        if ($this->editingParticipationId) {
            $participation = VolunteerParticipation::findOrFail($this->editingParticipationId);
            $oldValues = $participation->toArray();
            $participation->update($payload);

            AuditService::log(
                "Updated participation log '{$participation->activity_name}' ({$participation->hours} hrs) for volunteer '{$volunteer->volunteer_code}'",
                'volunteer_participations',
                $participation->id,
                $oldValues,
                $payload
            );
            session()->flash('success', 'Participation record updated.');
        } else {
            $payload['created_by'] = Auth::id();
            $participation = VolunteerParticipation::create($payload);

            AuditService::log(
                "Logged participation '{$participation->activity_name}' ({$participation->hours} hrs) for volunteer '{$volunteer->volunteer_code}'",
                'volunteer_participations',
                $participation->id,
                null,
                $payload
            );
            session()->flash('success', 'Participation and hours recorded.');
        }

        $this->showParticipationModal = false;
        if ($this->showViewModal && $this->viewVolunteer?->id === $volunteer->id) {
            $this->openViewModal($volunteer->id);
        }
    }

    public function deleteParticipation(int $participationId): void
    {
        $participation = VolunteerParticipation::with('volunteerProfile')->findOrFail($participationId);
        $volunteer = $participation->volunteerProfile;
        Gate::authorize('deleteParticipation', $volunteer);

        $activityName = $participation->activity_name;
        $hours = $participation->hours;
        $participation->delete();

        AuditService::log(
            "Deleted participation record '{$activityName}' ({$hours} hrs) for volunteer '{$volunteer->volunteer_code}'",
            'volunteer_participations',
            $participationId,
            ['activity_name' => $activityName, 'hours' => $hours],
            null
        );

        if ($this->showViewModal && $this->viewVolunteer?->id === $volunteer->id) {
            $this->openViewModal($volunteer->id);
        }
        session()->flash('success', 'Participation record deleted.');
    }

    /* -------------------------------------------------------------
     | CSV Export
     | ------------------------------------------------------------*/

    public function exportCsv(): StreamedResponse
    {
        Gate::authorize('export', VolunteerProfile::class);

        $districtId = $this->districtFilter ? (int) $this->districtFilter : null;
        $areaId = $this->areaFilter ? (int) $this->areaFilter : null;
        $communityId = $this->communityFilter ? (int) $this->communityFilter : null;

        $volunteers = VolunteerProfile::forUserScope()
            ->search($this->search)
            ->filterStatus($this->statusFilter)
            ->filterLocation($districtId, $areaId, $communityId)
            ->filterSkill($this->skillFilter)
            ->filterAvailability($this->availabilityFilter)
            ->with(['member.district', 'member.area', 'member.community', 'participations'])
            ->orderBy('id', 'desc')
            ->get();

        AuditService::log('Exported volunteer directory CSV report (' . $volunteers->count() . ' records)', 'volunteer_profiles');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="volunteers_export_' . date('Y_m_d_His') . '.csv"',
        ];

        return response()->stream(function () use ($volunteers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Volunteer Code',
                'Member Code',
                'Name',
                'Phone',
                'Email',
                'District',
                'Area',
                'Community',
                'Status',
                'Application Date',
                'Availability',
                'Skills',
                'Interests',
                'Total Volunteer Hours',
            ]);

            foreach ($volunteers as $v) {
                fputcsv($handle, [
                    $v->volunteer_code,
                    $v->member?->member_code ?? '',
                    $v->member?->full_name ?? '',
                    $v->member?->phone ?? '',
                    $v->member?->email ?? '',
                    $v->member?->district?->name ?? '',
                    $v->member?->area?->name ?? '',
                    $v->member?->community?->name ?? '',
                    ucfirst($v->volunteer_status),
                    $v->application_date ? $v->application_date->format('Y-m-d') : '',
                    ucfirst($v->availability ?? ''),
                    is_array($v->skills) ? implode(', ', $v->skills) : '',
                    is_array($v->interests) ? implode(', ', $v->interests) : '',
                    number_format($v->total_hours, 2),
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }

    /* -------------------------------------------------------------
     | Render
     | ------------------------------------------------------------*/

    public function render()
    {
        $districtId = $this->districtFilter ? (int) $this->districtFilter : null;
        $areaId = $this->areaFilter ? (int) $this->areaFilter : null;
        $communityId = $this->communityFilter ? (int) $this->communityFilter : null;

        $query = VolunteerProfile::forUserScope()
            ->search($this->search)
            ->filterStatus($this->statusFilter)
            ->filterLocation($districtId, $areaId, $communityId)
            ->filterSkill($this->skillFilter)
            ->filterAvailability($this->availabilityFilter)
            ->with(['member.district', 'member.area', 'member.community', 'member.profilePhoto', 'participations'])
            ->orderBy('id', 'desc');

        $volunteers = $query->paginate(15);

        // Database Calculated Statistics
        $stats = [
            'total' => VolunteerProfile::forUserScope()->count(),
            'active' => VolunteerProfile::forUserScope()->where('volunteer_status', 'active')->count(),
            'pending' => VolunteerProfile::forUserScope()->where('volunteer_status', 'pending')->count(),
            'approved' => VolunteerProfile::forUserScope()->where('volunteer_status', 'approved')->count(),
            'inactive' => VolunteerProfile::forUserScope()->where('volunteer_status', 'inactive')->count(),
            'suspended' => VolunteerProfile::forUserScope()->where('volunteer_status', 'suspended')->count(),
            'rejected' => VolunteerProfile::forUserScope()->where('volunteer_status', 'rejected')->count(),
            'archived' => VolunteerProfile::onlyTrashed()->count(),
            'total_hours' => (float) VolunteerParticipation::whereHas('volunteerProfile', fn($q) => $q->forUserScope())->sum('hours'),
        ];

        // Dropdown Lists
        $districts = District::where('is_active', true)->orderBy('name')->get();

        $filterAreas = collect();
        if ($districtId) {
            $filterAreas = Area::where('district_id', $districtId)->orderBy('name')->get();
        }

        $filterCommunities = collect();
        if ($areaId) {
            $filterCommunities = Community::where('area_id', $areaId)->orderBy('name')->get();
        }

        // Eligible Member candidates during onboarding
        $eligibleMembersList = collect();
        if ($this->showCreateModal && !empty($this->memberSearch)) {
            $eligibleMembersList = Member::forUserScope()
                ->whereDoesntHave('volunteerProfile')
                ->search($this->memberSearch)
                ->take(8)
                ->get();
        }

        return view('livewire.admin.volunteer-management', [
            'volunteers' => $volunteers,
            'stats' => $stats,
            'districts' => $districts,
            'filterAreas' => $filterAreas,
            'filterCommunities' => $filterCommunities,
            'eligibleMembersList' => $eligibleMembersList,
            'availableSkills' => $this->availableSkills,
            'availableInterests' => $this->availableInterests,
        ]);
    }
}
