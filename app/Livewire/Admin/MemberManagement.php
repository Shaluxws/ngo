<?php

namespace App\Livewire\Admin;

use App\Models\Area;
use App\Models\Community;
use App\Models\District;
use App\Models\Media;
use App\Models\Member;
use App\Services\AuditService;
use App\Services\FileSecurityService;
use App\Services\MemberCodeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.admin')]
class MemberManagement extends Component
{
    use WithPagination, WithFileUploads;

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

    // Modals
    public bool $showFormModal = false;
    public bool $isEditing = false;
    public ?int $selectedMemberId = null;

    public bool $showViewModal = false;
    public ?Member $viewMember = null;
    public array $viewMemberAuditLogs = [];

    public bool $showStatusModal = false;
    public ?int $statusMemberId = null;
    public string $statusMemberCode = '';
    public string $statusMemberName = '';
    public string $oldStatus = '';
    public string $newStatus = 'active';

    public bool $showMediaPickerModal = false;
    public string $mediaSearch = '';

    // Profile Photo File Upload
    public $profilePhotoFile = null;

    // Form Data
    public array $formData = [
        'first_name' => '',
        'last_name' => '',
        'date_of_birth' => '',
        'gender' => 'male',
        'phone' => '',
        'email' => '',
        'district_id' => '',
        'area_id' => '',
        'community_id' => '',
        'joined_at' => '',
        'status' => 'active',
        'notes' => '',
        'profile_photo_id' => null,
        'profile_photo_url' => '',
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

    /* -------------------------------------------------------------
     | Reactive Dependent Dropdowns for Member Form
     | ------------------------------------------------------------*/

    public function updatedFormDataDistrictId(): void
    {
        $this->formData['area_id'] = '';
        $this->formData['community_id'] = '';
    }

    public function updatedFormDataAreaId(): void
    {
        $this->formData['community_id'] = '';
    }

    /* -------------------------------------------------------------
     | Profile Photo Handling
     | ------------------------------------------------------------*/

    public function updatedProfilePhotoFile(): void
    {
        $this->validate([
            'profilePhotoFile' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        try {
            FileSecurityService::validateAndSanitize($this->profilePhotoFile, 'profilePhotoFile');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('profilePhotoFile', $e->getMessage());
            return;
        }

        $originalName = $this->profilePhotoFile->getClientOriginalName();
        $mimeType = $this->profilePhotoFile->getMimeType() ?: 'image/jpeg';
        $fileSize = $this->profilePhotoFile->getSize();

        $path = $this->profilePhotoFile->store('media', 'public');
        $fileName = basename($path);

        $media = Media::create([
            'file_name' => $fileName,
            'original_name' => $originalName,
            'file_path' => $path,
            'disk' => 'public',
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'alt_text' => "Profile photo of member",
            'title' => $originalName,
            'uploaded_by' => Auth::id(),
        ]);

        $this->formData['profile_photo_id'] = $media->id;
        $this->formData['profile_photo_url'] = $media->url;
        $this->profilePhotoFile = null;

        session()->flash('success', 'Profile photo uploaded successfully.');
    }

    public function openMediaPicker(): void
    {
        $this->showMediaPickerModal = true;
    }

    public function selectMedia(int $mediaId, string $url): void
    {
        $this->formData['profile_photo_id'] = $mediaId;
        $this->formData['profile_photo_url'] = $url;
        $this->showMediaPickerModal = false;
        session()->flash('success', 'Profile photo selected from Media Library.');
    }

    public function removeProfilePhoto(): void
    {
        $this->formData['profile_photo_id'] = null;
        $this->formData['profile_photo_url'] = '';
    }

    /* -------------------------------------------------------------
     | CRUD Workflows
     | ------------------------------------------------------------*/

    public function openCreateModal(): void
    {
        Gate::authorize('create', Member::class);

        $this->isEditing = false;
        $this->selectedMemberId = null;
        $this->resetValidation();

        $this->formData = [
            'first_name' => '',
            'last_name' => '',
            'date_of_birth' => '',
            'gender' => 'male',
            'phone' => '',
            'email' => '',
            'district_id' => District::where('is_active', true)->first()?->id ?? '',
            'area_id' => '',
            'community_id' => '',
            'joined_at' => date('Y-m-d'),
            'status' => 'active',
            'notes' => '',
            'profile_photo_id' => null,
            'profile_photo_url' => '',
        ];

        $this->showFormModal = true;
    }

    public function openEditModal(int $id): void
    {
        $member = Member::withTrashed()->findOrFail($id);
        Gate::authorize('update', $member);

        $this->isEditing = true;
        $this->selectedMemberId = $member->id;
        $this->resetValidation();

        $this->formData = [
            'first_name' => $member->first_name,
            'last_name' => $member->last_name ?? '',
            'date_of_birth' => $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '',
            'gender' => $member->gender ?? 'male',
            'phone' => $member->phone ?? '',
            'email' => $member->email ?? '',
            'district_id' => $member->district_id ?? '',
            'area_id' => $member->area_id ?? '',
            'community_id' => $member->community_id ?? '',
            'joined_at' => $member->joined_at ? $member->joined_at->format('Y-m-d') : date('Y-m-d'),
            'status' => $member->status,
            'notes' => $member->notes ?? '',
            'profile_photo_id' => $member->profile_photo_id,
            'profile_photo_url' => $member->profile_photo_url ?? '',
        ];

        $this->showFormModal = true;
    }

    public function saveMember(): void
    {
        if ($this->isEditing) {
            $member = Member::withTrashed()->findOrFail($this->selectedMemberId);
            Gate::authorize('update', $member);
        } else {
            Gate::authorize('create', Member::class);
        }

        $validated = $this->validate([
            'formData.first_name' => 'required|string|max:100',
            'formData.last_name' => 'nullable|string|max:100',
            'formData.date_of_birth' => 'nullable|date|before:today',
            'formData.gender' => 'required|in:male,female,other',
            'formData.phone' => 'nullable|string|max:30',
            'formData.email' => 'nullable|email|max:150',
            'formData.district_id' => 'nullable|exists:districts,id',
            'formData.area_id' => 'nullable|exists:areas,id',
            'formData.community_id' => 'nullable|exists:communities,id',
            'formData.joined_at' => 'required|date',
            'formData.status' => 'required|in:pending,active,inactive,suspended,archived',
            'formData.notes' => 'nullable|string|max:1000',
            'formData.profile_photo_id' => 'nullable|exists:media,id',
        ], [
            'formData.first_name.required' => 'First name is required.',
            'formData.gender.required' => 'Please select a gender.',
            'formData.joined_at.required' => 'Join date is required.',
        ]);

        $payload = [
            'first_name' => trim($this->formData['first_name']),
            'last_name' => !empty($this->formData['last_name']) ? trim($this->formData['last_name']) : null,
            'date_of_birth' => !empty($this->formData['date_of_birth']) ? $this->formData['date_of_birth'] : null,
            'gender' => $this->formData['gender'],
            'phone' => !empty($this->formData['phone']) ? trim($this->formData['phone']) : null,
            'email' => !empty($this->formData['email']) ? trim(strtolower($this->formData['email'])) : null,
            'district_id' => !empty($this->formData['district_id']) ? (int) $this->formData['district_id'] : null,
            'area_id' => !empty($this->formData['area_id']) ? (int) $this->formData['area_id'] : null,
            'community_id' => !empty($this->formData['community_id']) ? (int) $this->formData['community_id'] : null,
            'joined_at' => $this->formData['joined_at'],
            'status' => $this->formData['status'],
            'notes' => !empty($this->formData['notes']) ? trim($this->formData['notes']) : null,
            'profile_photo_id' => !empty($this->formData['profile_photo_id']) ? (int) $this->formData['profile_photo_id'] : null,
        ];

        if ($this->isEditing) {
            $oldValues = $member->only(array_keys($payload));
            $payload['updated_by'] = Auth::id();
            $member->update($payload);

            AuditService::log(
                "Updated member profile '{$member->member_code}' ({$member->full_name})",
                'members',
                $member->id,
                $oldValues,
                $payload
            );

            session()->flash('success', "Member '{$member->member_code}' updated successfully.");
        } else {
            $payload['member_code'] = MemberCodeService::generate();
            $payload['created_by'] = Auth::id();
            $payload['updated_by'] = Auth::id();

            $member = Member::create($payload);

            AuditService::log(
                "Created new member '{$member->member_code}' ({$member->full_name})",
                'members',
                $member->id,
                null,
                $payload
            );

            session()->flash('success', "New Member '{$member->member_code}' registered successfully.");
        }

        $this->showFormModal = false;
        $this->selectedMemberId = null;
    }

    public function openViewModal(int $id): void
    {
        $this->viewMember = Member::withTrashed()
            ->with(['district', 'area', 'community', 'profilePhoto', 'creator', 'updater', 'volunteerProfile.participations'])
            ->findOrFail($id);

        Gate::authorize('view', $this->viewMember);

        $this->viewMemberAuditLogs = \App\Models\AuditLog::where('entity_type', 'members')
            ->where('entity_id', $this->viewMember->id)
            ->with('user')
            ->latest('id')
            ->take(10)
            ->get()
            ->toArray();

        $this->showViewModal = true;
    }

    /* -------------------------------------------------------------
     | Status Management
     | ------------------------------------------------------------*/

    public function openStatusModal(int $id): void
    {
        $member = Member::withTrashed()->findOrFail($id);
        Gate::authorize('changeStatus', $member);

        $this->statusMemberId = $member->id;
        $this->statusMemberCode = $member->member_code;
        $this->statusMemberName = $member->full_name;
        $this->oldStatus = $member->status;
        $this->newStatus = $member->status;
        $this->showStatusModal = true;
    }

    public function updateStatus(): void
    {
        $member = Member::withTrashed()->findOrFail($this->statusMemberId);
        Gate::authorize('changeStatus', $member);

        $this->validate([
            'newStatus' => 'required|in:pending,active,inactive,suspended,archived',
        ]);

        $oldStatus = $member->status;
        $member->status = $this->newStatus;
        $member->updated_by = Auth::id();
        $member->save();

        AuditService::log(
            "Changed status for member '{$member->member_code}' from '{$oldStatus}' to '{$this->newStatus}'",
            'members',
            $member->id,
            ['status' => $oldStatus],
            ['status' => $this->newStatus]
        );

        $this->showStatusModal = false;
        session()->flash('success', "Member '{$member->member_code}' status updated to " . ucfirst($this->newStatus) . '.');
    }

    /* -------------------------------------------------------------
     | Archive, Restore & Delete
     | ------------------------------------------------------------*/

    public function archiveMember(int $id): void
    {
        $member = Member::findOrFail($id);
        Gate::authorize('delete', $member);

        $member->status = 'archived';
        $member->updated_by = Auth::id();
        $member->save();
        $member->delete(); // Soft delete

        AuditService::log(
            "Archived member '{$member->member_code}' ({$member->full_name})",
            'members',
            $member->id,
            ['status' => 'active'],
            ['status' => 'archived', 'deleted_at' => now()]
        );

        session()->flash('success', "Member '{$member->member_code}' has been archived.");
    }

    public function restoreMember(int $id): void
    {
        $member = Member::onlyTrashed()->findOrFail($id);
        Gate::authorize('restore', $member);

        $member->restore();
        $member->status = 'active';
        $member->updated_by = Auth::id();
        $member->save();

        AuditService::log(
            "Restored archived member '{$member->member_code}' ({$member->full_name})",
            'members',
            $member->id,
            ['status' => 'archived'],
            ['status' => 'active']
        );

        session()->flash('success', "Member '{$member->member_code}' restored to Active status.");
    }

    /* -------------------------------------------------------------
     | Export to CSV
     | ------------------------------------------------------------*/

    public function exportCsv(): StreamedResponse
    {
        Gate::authorize('export', Member::class);

        $districtId = $this->districtFilter ? (int) $this->districtFilter : null;
        $areaId = $this->areaFilter ? (int) $this->areaFilter : null;
        $communityId = $this->communityFilter ? (int) $this->communityFilter : null;

        $members = Member::forUserScope()
            ->search($this->search)
            ->filterStatus($this->statusFilter)
            ->filterLocation($districtId, $areaId, $communityId)
            ->with(['district', 'area', 'community'])
            ->orderBy('id', 'desc')
            ->get();

        AuditService::log('Exported members CSV report (' . $members->count() . ' records)', 'members');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="members_export_' . date('Y_m_d_His') . '.csv"',
        ];

        return response()->stream(function () use ($members) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Member ID',
                'First Name',
                'Last Name',
                'Gender',
                'Date of Birth',
                'Phone',
                'Email',
                'District',
                'Area',
                'Community',
                'Joined Date',
                'Status',
            ]);

            foreach ($members as $member) {
                fputcsv($handle, [
                    $member->member_code,
                    $member->first_name,
                    $member->last_name ?? '',
                    ucfirst($member->gender ?? ''),
                    $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '',
                    $member->phone ?? '',
                    $member->email ?? '',
                    $member->district?->name ?? '',
                    $member->area?->name ?? '',
                    $member->community?->name ?? '',
                    $member->joined_at ? $member->joined_at->format('Y-m-d') : '',
                    ucfirst($member->status),
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

        $query = Member::forUserScope()
            ->search($this->search)
            ->filterStatus($this->statusFilter)
            ->filterLocation($districtId, $areaId, $communityId)
            ->with(['district', 'area', 'community', 'profilePhoto'])
            ->orderBy('id', 'desc');

        $members = $query->paginate(15);

        // Statistics calculated directly from database
        $stats = [
            'total' => Member::forUserScope()->count(),
            'active' => Member::forUserScope()->where('status', 'active')->count(),
            'pending' => Member::forUserScope()->where('status', 'pending')->count(),
            'inactive' => Member::forUserScope()->where('status', 'inactive')->count(),
            'suspended' => Member::forUserScope()->where('status', 'suspended')->count(),
            'archived' => Member::onlyTrashed()->count(),
        ];

        // Dropdown Lists
        $districts = District::where('is_active', true)->orderBy('name')->get();
        
        $formAreas = collect();
        if (!empty($this->formData['district_id'])) {
            $formAreas = Area::where('district_id', $this->formData['district_id'])->orderBy('name')->get();
        }

        $formCommunities = collect();
        if (!empty($this->formData['area_id'])) {
            $formCommunities = Community::where('area_id', $this->formData['area_id'])->orderBy('name')->get();
        }

        $filterAreas = collect();
        if ($districtId) {
            $filterAreas = Area::where('district_id', $districtId)->orderBy('name')->get();
        }

        $filterCommunities = collect();
        if ($areaId) {
            $filterCommunities = Community::where('area_id', $areaId)->orderBy('name')->get();
        }

        $mediaList = collect();
        if ($this->showMediaPickerModal) {
            $mediaQuery = Media::latest();
            if (!empty($this->mediaSearch)) {
                $mediaQuery->where(function ($q) {
                    $q->where('original_name', 'like', "%{$this->mediaSearch}%")
                      ->orWhere('alt_text', 'like', "%{$this->mediaSearch}%");
                });
            }
            $mediaList = $mediaQuery->take(24)->get();
        }

        return view('livewire.admin.member-management', [
            'members' => $members,
            'stats' => $stats,
            'districts' => $districts,
            'formAreas' => $formAreas,
            'formCommunities' => $formCommunities,
            'filterAreas' => $filterAreas,
            'filterCommunities' => $filterCommunities,
            'mediaList' => $mediaList,
        ]);
    }
}
