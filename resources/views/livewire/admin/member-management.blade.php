<div class="space-y-6">

    <!-- Page Header & Statistics -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight font-display">Member Management</h1>
            <p class="text-xs text-slate-500 mt-1">Manage grassroots community members, onboarding, status, and community assignments across Tamil Nadu.</p>
        </div>
        <div class="flex items-center gap-2">
            @can('members.export')
                <button type="button" 
                        wire:click="exportCsv" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 shadow-2xs transition-all">
                    <i data-lucide="download" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Export CSV</span>
                </button>
            @endcan

            @can('members.create')
                <button type="button" 
                        wire:click="openCreateModal" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-sm shadow-emerald-900/20 transition-all">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>+ Add Member</span>
                </button>
            @endcan
        </div>
    </div>

    <!-- Feedback Alerts -->
    @if (session()->has('success'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between animate-fade-in">
            <div class="flex items-center gap-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-600"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between animate-fade-in">
            <div class="flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-900">
                <i data-lucide="x" class="w-3.5 h-3.5"></i>
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Members</span>
            <span class="text-xl font-bold text-slate-900 mt-1 block">{{ number_format($stats['total']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">Active</span>
            <span class="text-xl font-bold text-emerald-700 mt-1 block">{{ number_format($stats['active']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider block">Pending</span>
            <span class="text-xl font-bold text-amber-700 mt-1 block">{{ number_format($stats['pending']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Inactive</span>
            <span class="text-xl font-bold text-slate-700 mt-1 block">{{ number_format($stats['inactive']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider block">Suspended</span>
            <span class="text-xl font-bold text-rose-700 mt-1 block">{{ number_format($stats['suspended']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-purple-600 uppercase tracking-wider block">Archived</span>
            <span class="text-xl font-bold text-purple-700 mt-1 block">{{ number_format($stats['archived']) }}</span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            
            <!-- Search Query -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search by ID, name, phone, email..."
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="statusFilter" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                    <option value="all">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                    <option value="archived">Archived</option>
                    <option value="deleted">Soft Deleted</option>
                </select>
            </div>

            <!-- District Filter -->
            <div>
                <select wire:model.live="districtFilter" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                    <option value="">All Districts</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Area Filter -->
            <div>
                <select wire:model.live="areaFilter" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600" {{ empty($districtFilter) ? 'disabled' : '' }}>
                    <option value="">All Areas</option>
                    @foreach($filterAreas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if($areaFilter)
            <div class="flex items-center gap-2 pt-2 border-t border-slate-100">
                <span class="text-[11px] font-medium text-slate-500">Community Filter:</span>
                <select wire:model.live="communityFilter" class="py-1 px-2.5 text-xs rounded-lg border border-slate-200 bg-slate-50">
                    <option value="">All Communities in Selected Area</option>
                    @foreach($filterCommunities as $community)
                        <option value="{{ $community->id }}">{{ $community->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-semibold">
                    <tr>
                        <th class="py-3 px-4">Member</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Location</th>
                        <th class="py-3 px-4">Joined Date</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $member)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $member->trashed() ? 'bg-slate-50/40 opacity-75' : '' }}">
                            
                            <!-- Member Identification & Photo -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-slate-500 font-bold text-xs">
                                        @if($member->profilePhoto)
                                            <img src="{{ $member->profilePhoto->url }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span>{{ strtoupper(substr($member->first_name, 0, 1) . substr($member->last_name ?? '', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button" wire:click="openViewModal({{ $member->id }})" class="font-semibold text-slate-900 hover:text-emerald-700 text-left block truncate max-w-[180px]">
                                            {{ $member->full_name }}
                                        </button>
                                        <span class="inline-flex items-center gap-1 font-mono text-[10px] text-emerald-700 font-medium bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/60">
                                            {{ $member->member_code }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Contact -->
                            <td class="py-3 px-4">
                                <div class="space-y-0.5">
                                    @if($member->phone)
                                        <div class="flex items-center gap-1.5 text-slate-800">
                                            <i data-lucide="phone" class="w-3 h-3 text-slate-400"></i>
                                            <span>{{ $member->phone }}</span>
                                        </div>
                                    @endif
                                    @if($member->email)
                                        <div class="flex items-center gap-1.5 text-slate-500">
                                            <i data-lucide="mail" class="w-3 h-3 text-slate-400"></i>
                                            <span class="truncate max-w-[150px]">{{ $member->email }}</span>
                                        </div>
                                    @endif
                                    @if(!$member->phone && !$member->email)
                                        <span class="text-slate-400 italic">No contact details</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Location Hierarchy -->
                            <td class="py-3 px-4">
                                <div class="space-y-0.5">
                                    <div class="font-medium text-slate-900">
                                        {{ $member->community?->name ?? ($member->area?->name ?? 'Unassigned Area') }}
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $member->district?->name ?? 'Tamil Nadu' }}
                                    </div>
                                </div>
                            </td>

                            <!-- Joined Date -->
                            <td class="py-3 px-4 text-slate-600">
                                {{ $member->joined_at ? $member->joined_at->format('d M Y') : '—' }}
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3 px-4">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'inactive' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'suspended' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'archived' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ];
                                    $badgeClass = $statusClasses[$member->status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    <span>{{ ucfirst($member->status) }}</span>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" 
                                            wire:click="openViewModal({{ $member->id }})" 
                                            title="View Member Profile" 
                                            class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>

                                    @can('members.edit')
                                        <button type="button" 
                                                wire:click="openEditModal({{ $member->id }})" 
                                                title="Edit Member" 
                                                class="p-1.5 text-slate-500 hover:text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                    @endcan

                                    @can('members.suspend')
                                        <button type="button" 
                                                wire:click="openStatusModal({{ $member->id }})" 
                                                title="Change Status" 
                                                class="p-1.5 text-slate-500 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition-colors">
                                            <i data-lucide="sliders" class="w-4 h-4"></i>
                                        </button>
                                    @endcan

                                    @if($member->trashed() || $member->status === 'archived')
                                        @can('members.restore')
                                            <button type="button" 
                                                    wire:click="restoreMember({{ $member->id }})" 
                                                    title="Restore Member" 
                                                    class="p-1.5 text-emerald-600 hover:text-emerald-800 rounded-lg hover:bg-emerald-50 transition-colors">
                                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    @else
                                        @can('members.delete')
                                            <button type="button" 
                                                    wire:click="archiveMember({{ $member->id }})" 
                                                    title="Archive Member" 
                                                    class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition-colors">
                                                <i data-lucide="archive" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-500">
                                <div class="max-w-xs mx-auto space-y-2">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                        <i data-lucide="users" class="w-6 h-6"></i>
                                    </div>
                                    <p class="font-semibold text-slate-800">No members found</p>
                                    <p class="text-xs text-slate-500">Try adjusting your search terms or filter criteria, or register a new member.</p>
                                    @can('members.create')
                                        <button type="button" wire:click="openCreateModal" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition-all mt-2">
                                            + Add Member
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $members->links() }}
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- CREATE / EDIT MEMBER MODAL -->
    <!-- ========================================================================= -->
    @if($showFormModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 animate-fade-in" @click.away="$wire.showFormModal = false">
                
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 font-display">
                            {{ $isEditing ? 'Edit Member Profile' : 'Register New Member' }}
                        </h2>
                        <p class="text-xs text-slate-500">
                            {{ $isEditing ? 'Update grassroots member profile information and status.' : 'Add a grassroots community member to Nanban Social Foundation.' }}
                        </p>
                    </div>
                    <button type="button" wire:click="$set('showFormModal', false)" class="text-slate-400 hover:text-slate-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit="saveMember" class="p-6 space-y-6 max-h-[calc(85vh-120px)] overflow-y-auto">
                    
                    <!-- Section: Profile Photo -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-2">Member Profile Photo</label>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                @if(!empty($formData['profile_photo_url']))
                                    <img src="{{ $formData['profile_photo_url'] }}" alt="Profile Photo" class="w-full h-full object-cover">
                                @else
                                    <i data-lucide="user" class="w-7 h-7 text-slate-400"></i>
                                @endif
                            </div>
                            <div class="space-y-1.5 flex-1">
                                <div class="flex items-center gap-2">
                                    <label class="cursor-pointer inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 shadow-2xs">
                                        <i data-lucide="upload" class="w-3.5 h-3.5 text-slate-500"></i>
                                        <span>Upload Device Image</span>
                                        <input type="file" wire:model="profilePhotoFile" accept="image/jpeg,image/png,image/webp" class="hidden">
                                    </label>

                                    <button type="button" wire:click="openMediaPicker" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 shadow-2xs">
                                        <i data-lucide="images" class="w-3.5 h-3.5 text-slate-500"></i>
                                        <span>Media Library</span>
                                    </button>

                                    @if(!empty($formData['profile_photo_url']))
                                        <button type="button" wire:click="removeProfilePhoto" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-medium text-rose-600 hover:bg-rose-50">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            <span>Remove</span>
                                        </button>
                                    @endif
                                </div>
                                <span class="text-[11px] text-slate-400 block">Accepted: JPG, PNG, WEBP (Max 5MB)</span>
                                @error('profilePhotoFile') <span class="text-rose-600 text-xs block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Personal Information -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Personal Information</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">First Name <span class="text-rose-500">*</span></label>
                                <input type="text" wire:model="formData.first_name" placeholder="e.g. Ramesh" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                @error('formData.first_name') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Last Name / Initial</label>
                                <input type="text" wire:model="formData.last_name" placeholder="e.g. Kumar" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                @error('formData.last_name') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Date of Birth</label>
                                <input type="date" wire:model="formData.date_of_birth" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                @error('formData.date_of_birth') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Gender <span class="text-rose-500">*</span></label>
                                <select wire:model="formData.gender" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other / Prefer not to say</option>
                                </select>
                                @error('formData.gender') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Contact Details -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Contact Information</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Phone Number</label>
                                <input type="text" wire:model="formData.phone" placeholder="e.g. +91 98400 12345" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                @error('formData.phone') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Email Address</label>
                                <input type="email" wire:model="formData.email" placeholder="e.g. ramesh@example.com" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                @error('formData.email') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section: Location Assignment (Dependent Dropdowns) -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Location Assignment (Tamil Nadu Hierarchy)</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">District</label>
                                <select wire:model.live="formData.district_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                    <option value="">Select District</option>
                                    @foreach($districts as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Area / Taluk</label>
                                <select wire:model.live="formData.area_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600" {{ empty($formData['district_id']) ? 'disabled' : '' }}>
                                    <option value="">Select Area</option>
                                    @foreach($formAreas as $a)
                                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Community Focal Point</label>
                                <select wire:model="formData.community_id" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600" {{ empty($formData['area_id']) ? 'disabled' : '' }}>
                                    <option value="">Select Community</option>
                                    @foreach($formCommunities as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Membership Details -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">4. Membership & Status</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Joined Date <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="formData.joined_at" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                @error('formData.joined_at') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Initial Status <span class="text-rose-500">*</span></label>
                                <select wire:model="formData.status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                                    <option value="active">Active Member</option>
                                    <option value="pending">Pending Verification</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                                @error('formData.status') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-medium text-slate-700 mb-1">Admin Notes / Background</label>
                                <textarea wire:model="formData.notes" rows="2" placeholder="Optional notes regarding member skills, community role, etc." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600"></textarea>
                                @error('formData.notes') <span class="text-rose-600 text-[11px] block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showFormModal', false)" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-sm transition-all">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>{{ $isEditing ? 'Save Changes' : 'Register Member' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- VIEW MEMBER PROFILE MODAL -->
    <!-- ========================================================================= -->
    @if($showViewModal && $viewMember)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 animate-fade-in" @click.away="$wire.showViewModal = false">
                
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1 font-mono text-xs text-emerald-800 font-bold bg-emerald-100 px-2.5 py-1 rounded-md border border-emerald-200">
                            {{ $viewMember->member_code }}
                        </span>
                        <h2 class="text-base font-bold text-slate-900 font-display">Member Profile</h2>
                    </div>
                    <button type="button" wire:click="$set('showViewModal', false)" class="text-slate-400 hover:text-slate-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6 max-h-[calc(85vh-120px)] overflow-y-auto">
                    
                    <!-- Top Profile Card -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-slate-600 font-bold text-lg shadow-2xs">
                            @if($viewMember->profilePhoto)
                                <img src="{{ $viewMember->profilePhoto->url }}" alt="{{ $viewMember->full_name }}" class="w-full h-full object-cover">
                            @else
                                <span>{{ strtoupper(substr($viewMember->first_name, 0, 1) . substr($viewMember->last_name ?? '', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="space-y-1 text-center sm:text-left flex-1">
                            <h3 class="text-lg font-bold text-slate-900">{{ $viewMember->full_name }}</h3>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $viewMember->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($viewMember->status) }}
                                </span>
                                <span class="text-xs text-slate-500 font-medium">Joined {{ $viewMember->joined_at ? $viewMember->joined_at->format('d F Y') : '—' }}</span>
                            </div>
                        </div>
                        @can('members.edit')
                            <button type="button" wire:click="openEditModal({{ $viewMember->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                <span>Edit Profile</span>
                            </button>
                        @endcan
                    </div>

                    <!-- Details Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-white border border-slate-200/80 space-y-2">
                            <span class="text-[11px] font-bold uppercase text-slate-400 block tracking-wider">Contact Details</span>
                            <div class="text-xs space-y-1">
                                <p><span class="text-slate-500">Phone:</span> <span class="font-medium text-slate-900">{{ $viewMember->phone ?: 'Not provided' }}</span></p>
                                <p><span class="text-slate-500">Email:</span> <span class="font-medium text-slate-900">{{ $viewMember->email ?: 'Not provided' }}</span></p>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-white border border-slate-200/80 space-y-2">
                            <span class="text-[11px] font-bold uppercase text-slate-400 block tracking-wider">Personal Info</span>
                            <div class="text-xs space-y-1">
                                <p><span class="text-slate-500">Gender:</span> <span class="font-medium text-slate-900">{{ ucfirst($viewMember->gender ?? 'Not specified') }}</span></p>
                                <p><span class="text-slate-500">Date of Birth:</span> <span class="font-medium text-slate-900">{{ $viewMember->date_of_birth ? $viewMember->date_of_birth->format('d M Y') : 'Not specified' }}</span></p>
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-white border border-slate-200/80 space-y-2 sm:col-span-2">
                            <span class="text-[11px] font-bold uppercase text-slate-400 block tracking-wider">Location Hierarchy</span>
                            <div class="grid grid-cols-3 gap-2 text-xs">
                                <div>
                                    <span class="text-slate-400 block text-[10px]">District</span>
                                    <span class="font-semibold text-slate-900">{{ $viewMember->district?->name ?: 'Unassigned' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Area</span>
                                    <span class="font-semibold text-slate-900">{{ $viewMember->area?->name ?: 'Unassigned' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[10px]">Community Focal Point</span>
                                    <span class="font-semibold text-slate-900">{{ $viewMember->community?->name ?: 'Unassigned' }}</span>
                                </div>
                            </div>
                        </div>

                        @if($viewMember->notes)
                            <div class="p-4 rounded-xl bg-white border border-slate-200/80 space-y-2 sm:col-span-2">
                                <span class="text-[11px] font-bold uppercase text-slate-400 block tracking-wider">Admin Notes</span>
                                <p class="text-xs text-slate-700 leading-relaxed">{{ $viewMember->notes }}</p>
                            </div>
                        @endif

                        <!-- Volunteer Section -->
                        @can('volunteers.view')
                            <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-200/80 space-y-3 sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px] font-bold uppercase text-emerald-800 tracking-wider flex items-center gap-1.5">
                                        <i data-lucide="heart-handshake" class="w-3.5 h-3.5"></i>
                                        Volunteer Information
                                    </span>
                                    @if($viewMember->volunteerProfile)
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold uppercase font-mono
                                            @if($viewMember->volunteerProfile->volunteer_status === 'active') bg-emerald-100 text-emerald-800
                                            @elseif($viewMember->volunteerProfile->volunteer_status === 'approved') bg-blue-100 text-blue-800
                                            @elseif($viewMember->volunteerProfile->volunteer_status === 'pending') bg-amber-100 text-amber-800
                                            @elseif($viewMember->volunteerProfile->volunteer_status === 'suspended') bg-rose-100 text-rose-800
                                            @else bg-slate-100 text-slate-700 @endif">
                                            {{ $viewMember->volunteerProfile->volunteer_status }}
                                        </span>
                                    @endif
                                </div>

                                @if($viewMember->volunteerProfile)
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                                        <div class="p-2.5 rounded-lg bg-white border border-emerald-100">
                                            <span class="text-slate-400 block text-[10px]">Volunteer Code</span>
                                            <span class="font-mono font-bold text-emerald-950">{{ $viewMember->volunteerProfile->volunteer_code }}</span>
                                        </div>
                                        <div class="p-2.5 rounded-lg bg-white border border-emerald-100">
                                            <span class="text-slate-400 block text-[10px]">Total Hours</span>
                                            <span class="font-bold text-slate-900">{{ number_format($viewMember->volunteerProfile->total_hours, 1) }} hrs</span>
                                        </div>
                                        <div class="p-2.5 rounded-lg bg-white border border-emerald-100 flex items-center justify-between">
                                            <div>
                                                <span class="text-slate-400 block text-[10px]">Participations</span>
                                                <span class="font-bold text-slate-900">{{ $viewMember->volunteerProfile->participations->count() }} records</span>
                                            </div>
                                            <a href="{{ route('admin.volunteers') }}" class="text-emerald-700 hover:text-emerald-900 text-xs font-semibold underline">
                                                View in Volunteers →
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-slate-600 bg-white/70 p-3 rounded-lg border border-emerald-100">
                                        <span>This member does not have an active volunteer profile yet.</span>
                                        @can('volunteers.create')
                                            <a href="{{ route('admin.volunteers') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-emerald-900 bg-emerald-100 hover:bg-emerald-200 transition-colors">
                                                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                                                <span>Onboard as Volunteer</span>
                                            </a>
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        @endcan
                    </div>

                    <!-- Member Activity / Audit History -->
                    @if(count($viewMemberAuditLogs) > 0)
                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Activity History</h4>
                            <div class="space-y-2">
                                @foreach($viewMemberAuditLogs as $log)
                                    <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200/60 text-xs flex items-center justify-between">
                                        <div>
                                            <span class="font-medium text-slate-800">{{ $log['action'] }}</span>
                                            <span class="text-slate-400 text-[11px] block">By {{ $log['user']['name'] ?? 'System' }}</span>
                                        </div>
                                        <span class="text-[11px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($log['created_at'])->format('d M Y, H:i') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-end px-6 py-3 border-t border-slate-200 bg-slate-50/80">
                    <button type="button" wire:click="$set('showViewModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 transition-colors">
                        Close Profile
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- STATUS CHANGE MODAL -->
    <!-- ========================================================================= -->
    @if($showStatusModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-fade-in" @click.away="$wire.showStatusModal = false">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <h3 class="text-sm font-bold text-slate-900">Change Member Status</h3>
                    <button type="button" wire:click="$set('showStatusModal', false)" class="text-slate-400 hover:text-slate-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form wire:submit="updateStatus" class="p-6 space-y-4">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                        <p><span class="text-slate-500">Member:</span> <span class="font-bold text-slate-900">{{ $statusMemberName }}</span></p>
                        <p><span class="text-slate-500">Code:</span> <span class="font-mono text-emerald-700 font-semibold">{{ $statusMemberCode }}</span></p>
                        <p><span class="text-slate-500">Current Status:</span> <span class="capitalize font-medium text-slate-800">{{ $oldStatus }}</span></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">New Status</label>
                        <select wire:model="newStatus" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                            <option value="archived">Archived</option>
                        </select>
                        @error('newStatus') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showStatusModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- MEDIA LIBRARY PICKER MODAL -->
    <!-- ========================================================================= -->
    @if($showMediaPickerModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 animate-fade-in" @click.away="$wire.showMediaPickerModal = false">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Select Image from Media Library</h3>
                        <p class="text-xs text-slate-500">Pick an existing uploaded photo for this member.</p>
                    </div>
                    <button type="button" wire:click="$set('showMediaPickerModal', false)" class="text-slate-400 hover:text-slate-700">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4 max-h-[calc(75vh-120px)] overflow-y-auto">
                    <input type="text" wire:model.live.debounce.300ms="mediaSearch" placeholder="Search media library..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">

                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @forelse($mediaList as $m)
                            <div wire:click="selectMedia({{ $m->id }}, '{{ $m->url }}')" class="group relative aspect-square rounded-xl bg-slate-100 border border-slate-200 overflow-hidden cursor-pointer hover:border-emerald-600 hover:ring-2 hover:ring-emerald-600/30 transition-all">
                                <img src="{{ $m->url }}" alt="{{ $m->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                            </div>
                        @empty
                            <div class="col-span-full py-8 text-center text-xs text-slate-400">
                                No media files found. Upload a photo from your device.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="flex items-center justify-end px-6 py-3 border-t border-slate-200 bg-slate-50/80">
                    <button type="button" wire:click="$set('showMediaPickerModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>
