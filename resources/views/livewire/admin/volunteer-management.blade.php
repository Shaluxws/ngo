<div class="space-y-6">

    <!-- Page Header & Global Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight font-display">Volunteer Management</h1>
            <p class="text-xs text-slate-500 mt-1">Manage grassroots volunteers, review applications, assign community skills, and track service hours across Tamil Nadu.</p>
        </div>
        <div class="flex items-center gap-2">
            @can('volunteers.export')
                <button type="button" 
                        wire:click="exportCsv" 
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 shadow-2xs transition-all">
                    <i data-lucide="download" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>Export CSV</span>
                </button>
            @endcan

            @can('volunteers.create')
                <button type="button" 
                        wire:click="openCreateModal" 
                        class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-sm shadow-emerald-900/20 transition-all">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>+ Onboard Volunteer</span>
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

    <!-- KPI Metric Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Volunteers</span>
            <span class="text-xl font-bold text-slate-900 mt-1 block">{{ number_format($stats['total']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">Active</span>
            <span class="text-xl font-bold text-emerald-700 mt-1 block">{{ number_format($stats['active']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider block">Pending Review</span>
            <span class="text-xl font-bold text-amber-700 mt-1 block">{{ number_format($stats['pending']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-blue-600 uppercase tracking-wider block">Approved</span>
            <span class="text-xl font-bold text-blue-700 mt-1 block">{{ number_format($stats['approved']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-rose-600 uppercase tracking-wider block">Rejected / Suspended</span>
            <span class="text-xl font-bold text-rose-700 mt-1 block">{{ number_format($stats['rejected'] + $stats['suspended']) }}</span>
        </div>
        <div class="p-4 rounded-2xl bg-white border border-slate-200/80 shadow-2xs">
            <span class="text-[11px] font-semibold text-purple-600 uppercase tracking-wider block">Total Service Hours</span>
            <span class="text-xl font-bold text-purple-700 mt-1 block">{{ number_format($stats['total_hours'], 1) }} hrs</span>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-2xs space-y-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
            
            <!-- Search Input -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search volunteer ID, name, phone, email..."
                       class="w-full pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
            </div>

            <!-- Status Filter -->
            <div>
                <select wire:model.live="statusFilter" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                    <option value="all">All Statuses</option>
                    <option value="pending">Pending Review</option>
                    <option value="approved">Approved</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                    <option value="rejected">Rejected</option>
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

            <!-- Availability Filter -->
            <div>
                <select wire:model.live="availabilityFilter" class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-emerald-600/20 focus:border-emerald-600">
                    <option value="all">All Availability</option>
                    <option value="weekdays">Weekdays</option>
                    <option value="weekends">Weekends</option>
                    <option value="both">Both Weekdays & Weekends</option>
                    <option value="flexible">Flexible / On-call</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-[11px] font-medium text-slate-500">Filter by Skill:</span>
                <select wire:model.live="skillFilter" class="py-1 px-2.5 text-xs rounded-lg border border-slate-200 bg-slate-50">
                    <option value="all">All Skills</option>
                    @foreach($availableSkills as $skill)
                        <option value="{{ $skill }}">{{ $skill }}</option>
                    @endforeach
                </select>
            </div>

            @if($areaFilter)
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-medium text-slate-500">Community:</span>
                    <select wire:model.live="communityFilter" class="py-1 px-2.5 text-xs rounded-lg border border-slate-200 bg-slate-50">
                        <option value="">All Communities in Area</option>
                        @foreach($filterCommunities as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        </div>
    </div>

    <!-- Volunteers Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50/75 border-b border-slate-200/80 text-[11px] uppercase tracking-wider text-slate-500 font-semibold">
                    <tr>
                        <th class="py-3 px-4">Volunteer</th>
                        <th class="py-3 px-4">Location</th>
                        <th class="py-3 px-4">Skills & Availability</th>
                        <th class="py-3 px-4">Total Service</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($volunteers as $v)
                        <tr class="hover:bg-slate-50/60 transition-colors {{ $v->trashed() ? 'bg-slate-50/40 opacity-75' : '' }}">
                            
                            <!-- Volunteer & Member Identity -->
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-slate-500 font-bold text-xs">
                                        @if($v->member?->profilePhoto)
                                            <img src="{{ $v->member->profilePhoto->url }}" alt="{{ $v->member->full_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span>{{ strtoupper(substr($v->member?->first_name ?? 'V', 0, 1) . substr($v->member?->last_name ?? '', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button" wire:click="openViewModal({{ $v->id }})" class="font-semibold text-slate-900 hover:text-emerald-700 text-left block truncate max-w-[180px]">
                                            {{ $v->member?->full_name ?? 'Unknown Member' }}
                                        </button>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="inline-flex items-center gap-1 font-mono text-[10px] text-emerald-800 font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/60">
                                                {{ $v->volunteer_code }}
                                            </span>
                                            <span class="text-[10px] text-slate-400 font-mono">
                                                {{ $v->member?->member_code }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Location Hierarchy -->
                            <td class="py-3 px-4">
                                <div class="space-y-0.5">
                                    <div class="font-medium text-slate-900">
                                        {{ $v->member?->community?->name ?? ($v->member?->area?->name ?? 'Unassigned') }}
                                    </div>
                                    <div class="text-[11px] text-slate-500">
                                        {{ $v->member?->district?->name ?? 'Tamil Nadu' }}
                                    </div>
                                </div>
                            </td>

                            <!-- Skills & Availability -->
                            <td class="py-3 px-4">
                                <div class="space-y-1">
                                    <div class="flex flex-wrap gap-1 max-w-xs">
                                        @if(is_array($v->skills) && count($v->skills) > 0)
                                            @foreach(array_slice($v->skills, 0, 2) as $s)
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                                                    {{ $s }}
                                                </span>
                                            @endforeach
                                            @if(count($v->skills) > 2)
                                                <span class="text-[10px] text-slate-400 font-medium">+{{ count($v->skills) - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-slate-400 italic text-[11px]">General Support</span>
                                        @endif
                                    </div>
                                    <span class="text-[11px] text-slate-500 block">
                                        {{ ucfirst($v->availability ?? 'flexible') }}
                                    </span>
                                </div>
                            </td>

                            <!-- Total Hours & Activities -->
                            <td class="py-3 px-4">
                                <div class="space-y-0.5">
                                    <span class="font-bold text-slate-900 block text-xs">
                                        {{ number_format($v->total_hours, 1) }} hrs
                                    </span>
                                    <span class="text-[11px] text-slate-400 block">
                                        {{ $v->participation_count }} activities
                                    </span>
                                </div>
                            </td>

                            <!-- Status Badge -->
                            <td class="py-3 px-4">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'approved' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'inactive' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'suspended' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'archived' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ];
                                    $badgeClass = $statusClasses[$v->volunteer_status] ?? 'bg-slate-100 text-slate-700 border-slate-200';
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    <span>{{ ucfirst($v->volunteer_status) }}</span>
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" 
                                            wire:click="openViewModal({{ $v->id }})" 
                                            title="View Volunteer Profile" 
                                            class="p-1.5 text-slate-500 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition-colors">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>

                                    @if($v->volunteer_status === 'pending')
                                        @can('volunteers.approve')
                                            <button type="button" 
                                                    wire:click="openApprovalModal({{ $v->id }})" 
                                                    title="Approve Volunteer" 
                                                    class="p-1.5 text-emerald-600 hover:text-emerald-800 rounded-lg hover:bg-emerald-50 transition-colors">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </button>
                                        @endcan

                                        @can('volunteers.reject')
                                            <button type="button" 
                                                    wire:click="openRejectionModal({{ $v->id }})" 
                                                    title="Reject Volunteer Application" 
                                                    class="p-1.5 text-rose-600 hover:text-rose-800 rounded-lg hover:bg-rose-50 transition-colors">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    @endif

                                    @can('volunteers.participation.create')
                                        <button type="button" 
                                                wire:click="openAddParticipation({{ $v->id }})" 
                                                title="Log Activity & Hours" 
                                                class="p-1.5 text-slate-500 hover:text-purple-700 rounded-lg hover:bg-purple-50 transition-colors">
                                            <i data-lucide="clock" class="w-4 h-4"></i>
                                        </button>
                                    @endcan

                                    @can('volunteers.edit')
                                        <button type="button" 
                                                wire:click="openEditModal({{ $v->id }})" 
                                                title="Edit Volunteer Details" 
                                                class="p-1.5 text-slate-500 hover:text-emerald-700 rounded-lg hover:bg-emerald-50 transition-colors">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                    @endcan

                                    @can('volunteers.suspend')
                                        <button type="button" 
                                                wire:click="openStatusModal({{ $v->id }})" 
                                                title="Change Status" 
                                                class="p-1.5 text-slate-500 hover:text-amber-700 rounded-lg hover:bg-amber-50 transition-colors">
                                            <i data-lucide="sliders" class="w-4 h-4"></i>
                                        </button>
                                    @endcan

                                    @if($v->trashed() || $v->volunteer_status === 'archived')
                                        @can('volunteers.restore')
                                            <button type="button" 
                                                    wire:click="restoreVolunteer({{ $v->id }})" 
                                                    title="Restore Volunteer" 
                                                    class="p-1.5 text-emerald-600 hover:text-emerald-800 rounded-lg hover:bg-emerald-50 transition-colors">
                                                <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
                                            </button>
                                        @endcan
                                    @else
                                        @can('volunteers.delete')
                                            <button type="button" 
                                                    wire:click="archiveVolunteer({{ $v->id }})" 
                                                    title="Archive Volunteer" 
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
                                        <i data-lucide="heart-handshake" class="w-6 h-6"></i>
                                    </div>
                                    <p class="font-semibold text-slate-800">No volunteers found</p>
                                    <p class="text-xs text-slate-500">No volunteer profiles match your search or filter criteria. Onboard a grassroots member as a volunteer.</p>
                                    @can('volunteers.create')
                                        <button type="button" wire:click="openCreateModal" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 transition-all mt-2">
                                            + Onboard Volunteer
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($volunteers->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $volunteers->links() }}
            </div>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- ONBOARD VOLUNTEER MODAL -->
    <!-- ========================================================================= -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 animate-fade-in" @click.away="$wire.showCreateModal = false">
                
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 font-display">Onboard Community Volunteer</h2>
                        <p class="text-xs text-slate-500">Create a volunteer profile linked directly to an existing grassroots member.</p>
                    </div>
                    <button type="button" wire:click="$set('showCreateModal', false)" class="text-slate-400 hover:text-slate-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit="saveNewVolunteer" class="p-6 space-y-6 max-h-[calc(85vh-120px)] overflow-y-auto">
                    
                    <!-- Step 1: Member Selection -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">1. Select Grassroots Member <span class="text-rose-500">*</span></h3>
                        
                        @if($selectedMember)
                            <div class="flex items-center justify-between p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-xs">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-200 text-emerald-800 font-bold flex items-center justify-center">
                                        {{ strtoupper(substr($selectedMember->first_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-emerald-950">{{ $selectedMember->full_name }}</p>
                                        <p class="text-emerald-700 font-mono text-[11px]">{{ $selectedMember->member_code }} • {{ $selectedMember->community?->name ?? 'Tamil Nadu' }}</p>
                                    </div>
                                </div>
                                <button type="button" wire:click="$set('selectedMemberId', null); $set('selectedMember', null)" class="text-xs text-emerald-800 font-semibold hover:underline">
                                    Change Member
                                </button>
                            </div>
                        @else
                            <div class="space-y-2">
                                <input type="text" 
                                       wire:model.live.debounce.300ms="memberSearch" 
                                       placeholder="Type member name, code, or phone number to search..." 
                                       class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-600/20">
                                
                                @if(count($eligibleMembersList) > 0)
                                    <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100 max-h-48 overflow-y-auto bg-white shadow-xs">
                                        @foreach($eligibleMembersList as $em)
                                            <div wire:click="selectMember({{ $em->id }})" class="p-2.5 flex items-center justify-between hover:bg-emerald-50/70 cursor-pointer transition-colors text-xs">
                                                <div>
                                                    <span class="font-semibold text-slate-900">{{ $em->full_name }}</span>
                                                    <span class="text-slate-400 font-mono text-[10px] ml-1.5">({{ $em->member_code }})</span>
                                                    <span class="text-slate-500 block text-[11px]">{{ $em->community?->name ?? $em->district?->name }}</span>
                                                </div>
                                                <button type="button" class="px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                    Select
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(strlen($memberSearch) >= 2)
                                    <p class="text-xs text-slate-400 italic">No eligible members found. (Members who are already volunteers or out of scope are excluded).</p>
                                @endif
                                
                                @error('selectedMemberId') <span class="text-rose-600 text-xs block">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>

                    <!-- Step 2: Volunteer Skills -->
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">2. Volunteer Skills & Specializations</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableSkills as $skill)
                                <button type="button" 
                                        wire:click="toggleSkill('{{ $skill }}')" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium border transition-all {{ in_array($skill, $formData['skills'], true) ? 'bg-emerald-700 text-white border-emerald-700 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                    {{ $skill }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 3: Core Interests -->
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">3. Areas of Passion & Interest</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableInterests as $interest)
                                <button type="button" 
                                        wire:click="toggleInterest('{{ $interest }}')" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium border transition-all {{ in_array($interest, $formData['interests'], true) ? 'bg-teal-700 text-white border-teal-700 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                    {{ $interest }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 4: Availability & Schedule -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">4. Availability & Status</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">General Availability <span class="text-rose-500">*</span></label>
                                <select wire:model="formData.availability" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20">
                                    <option value="both">Both Weekdays & Weekends</option>
                                    <option value="weekdays">Weekdays Only</option>
                                    <option value="weekends">Weekends Only</option>
                                    <option value="flexible">Flexible / On-Call</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Application Date <span class="text-rose-500">*</span></label>
                                <input type="date" wire:model="formData.application_date" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Initial Status <span class="text-rose-500">*</span></label>
                                <select wire:model="formData.volunteer_status" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20">
                                    <option value="pending">Pending Review</option>
                                    <option value="approved">Approved</option>
                                    <option value="active">Direct Active</option>
                                </select>
                            </div>

                            <div class="sm:col-span-3">
                                <label class="block text-xs font-medium text-slate-700 mb-1">Availability Notes / Schedule Preferences</label>
                                <input type="text" wire:model="formData.availability_notes" placeholder="e.g. Available Sundays 9 AM - 2 PM, free on public holidays" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600/20">
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Emergency Contact Details -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">5. Volunteer Emergency Contact</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Contact Name</label>
                                <input type="text" wire:model="formData.emergency_contact_name" placeholder="e.g. Meenakshi" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Contact Phone</label>
                                <input type="text" wire:model="formData.emergency_contact_phone" placeholder="e.g. +91 94420 55667" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Relationship</label>
                                <input type="text" wire:model="formData.emergency_contact_relation" placeholder="e.g. Spouse / Parent" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800 shadow-sm">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            <span>Complete Onboarding</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- EDIT VOLUNTEER MODAL -->
    <!-- ========================================================================= -->
    @if($showEditModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 animate-fade-in" @click.away="$wire.showEditModal = false">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <h2 class="text-base font-bold text-slate-900 font-display">Edit Volunteer Profile</h2>
                    <button type="button" wire:click="$set('showEditModal', false)" class="text-slate-400 hover:text-slate-700">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit="updateVolunteer" class="p-6 space-y-6 max-h-[calc(85vh-120px)] overflow-y-auto">
                    <!-- Skills -->
                    <div class="space-y-3">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Skills & Specializations</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableSkills as $skill)
                                <button type="button" 
                                        wire:click="toggleSkill('{{ $skill }}')" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium border transition-all {{ in_array($skill, $formData['skills'], true) ? 'bg-emerald-700 text-white border-emerald-700 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                    {{ $skill }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Interests -->
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Areas of Interest</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableInterests as $interest)
                                <button type="button" 
                                        wire:click="toggleInterest('{{ $interest }}')" 
                                        class="px-2.5 py-1 rounded-lg text-xs font-medium border transition-all {{ in_array($interest, $formData['interests'], true) ? 'bg-teal-700 text-white border-teal-700 shadow-2xs' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                                    {{ $interest }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Availability</label>
                                <select wire:model="formData.availability" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                                    <option value="both">Both Weekdays & Weekends</option>
                                    <option value="weekdays">Weekdays Only</option>
                                    <option value="weekends">Weekends Only</option>
                                    <option value="flexible">Flexible / On-Call</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Schedule Notes</label>
                                <input type="text" wire:model="formData.availability_notes" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="space-y-4 pt-4 border-t border-slate-100">
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Emergency Contact</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Contact Name</label>
                                <input type="text" wire:model="formData.emergency_contact_name" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Contact Phone</label>
                                <input type="text" wire:model="formData.emergency_contact_phone" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Relationship</label>
                                <input type="text" wire:model="formData.emergency_contact_relation" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" wire:click="$set('showEditModal', false)" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- VIEW VOLUNTEER PROFILE MODAL -->
    <!-- ========================================================================= -->
    @if($showViewModal && $viewVolunteer)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
            <div class="relative w-full max-w-3xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 animate-fade-in" @click.away="$wire.showViewModal = false">
                
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1 font-mono text-xs text-emerald-800 font-bold bg-emerald-100 px-2.5 py-1 rounded-md border border-emerald-200">
                            {{ $viewVolunteer->volunteer_code }}
                        </span>
                        <h2 class="text-base font-bold text-slate-900 font-display">Volunteer Service Profile</h2>
                    </div>
                    <button type="button" wire:click="$set('showViewModal', false)" class="text-slate-400 hover:text-slate-700 p-1">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-6 space-y-6 max-h-[calc(85vh-120px)] overflow-y-auto">
                    
                    <!-- Identity Header -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <div class="w-20 h-20 rounded-2xl bg-white border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-slate-600 font-bold text-lg shadow-2xs">
                            @if($viewVolunteer->member?->profilePhoto)
                                <img src="{{ $viewVolunteer->member->profilePhoto->url }}" alt="{{ $viewVolunteer->member->full_name }}" class="w-full h-full object-cover">
                            @else
                                <span>{{ strtoupper(substr($viewVolunteer->member?->first_name ?? 'V', 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="space-y-1 text-center sm:text-left flex-1">
                            <h3 class="text-lg font-bold text-slate-900">{{ $viewVolunteer->member?->full_name }}</h3>
                            <p class="text-xs text-slate-500 font-mono">{{ $viewVolunteer->member?->member_code }} • {{ $viewVolunteer->member?->community?->name ?? 'Tamil Nadu' }}</p>
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 pt-1">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $viewVolunteer->volunteer_status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($viewVolunteer->volunteer_status) }}
                                </span>
                                <span class="text-xs text-slate-500">Applied {{ $viewVolunteer->application_date ? $viewVolunteer->application_date->format('d M Y') : '—' }}</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2">
                            @can('volunteers.participation.create')
                                <button type="button" wire:click="openAddParticipation({{ $viewVolunteer->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-purple-700 hover:bg-purple-800 transition-colors">
                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                    <span>Log Service Hours</span>
                                </button>
                            @endcan
                        </div>
                    </div>

                    <!-- Skills & Availability -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-white border border-slate-200/80 space-y-2">
                            <span class="text-[11px] font-bold uppercase text-slate-400 block tracking-wider">Skills & Capabilities</span>
                            <div class="flex flex-wrap gap-1.5">
                                @if(is_array($viewVolunteer->skills) && count($viewVolunteer->skills) > 0)
                                    @foreach($viewVolunteer->skills as $sk)
                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                            {{ $sk }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-slate-400 italic text-xs">No specific skills tagged</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 rounded-xl bg-white border border-slate-200/80 space-y-2">
                            <span class="text-[11px] font-bold uppercase text-slate-400 block tracking-wider">Availability & Schedule</span>
                            <p class="text-xs font-semibold text-slate-800 capitalize">{{ $viewVolunteer->availability ?? 'Flexible' }}</p>
                            @if($viewVolunteer->availability_notes)
                                <p class="text-xs text-slate-600">{{ $viewVolunteer->availability_notes }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    @if($viewVolunteer->emergency_contact_name || $viewVolunteer->emergency_contact_phone)
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200/80 space-y-1">
                            <span class="text-[11px] font-bold uppercase text-slate-500 block tracking-wider">Volunteer Emergency Contact</span>
                            <div class="text-xs text-slate-700 flex flex-wrap gap-4">
                                <p><span class="text-slate-400">Name:</span> <span class="font-medium text-slate-900">{{ $viewVolunteer->emergency_contact_name }}</span></p>
                                <p><span class="text-slate-400">Phone:</span> <span class="font-medium text-slate-900">{{ $viewVolunteer->emergency_contact_phone }}</span></p>
                                <p><span class="text-slate-400">Relation:</span> <span class="font-medium text-slate-900">{{ $viewVolunteer->emergency_contact_relation }}</span></p>
                            </div>
                        </div>
                    @endif

                    <!-- Participation History & Logged Hours -->
                    <div class="space-y-3 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Service & Activity History</h4>
                                <p class="text-xs text-slate-500">Cumulative Service: <span class="font-bold text-purple-700">{{ number_format($viewVolunteer->total_hours, 1) }} Total Hours</span></p>
                            </div>
                            @can('volunteers.participation.create')
                                <button type="button" wire:click="openAddParticipation({{ $viewVolunteer->id }})" class="text-xs text-purple-700 font-semibold hover:underline">
                                    + Add Activity
                                </button>
                            @endcan
                        </div>

                        <div class="border border-slate-200 rounded-xl overflow-hidden divide-y divide-slate-100">
                            @forelse($viewVolunteer->participations as $p)
                                <div class="p-3 bg-white flex items-center justify-between text-xs hover:bg-slate-50/70 transition-colors">
                                    <div class="space-y-0.5">
                                        <p class="font-bold text-slate-900">{{ $p->activity_name }}</p>
                                        <p class="text-[11px] text-slate-500">Role: {{ $p->role ?? 'Volunteer' }} • Date: {{ $p->activity_date->format('d M Y') }}</p>
                                        @if($p->notes)
                                            <p class="text-[11px] text-slate-600 italic">{{ $p->notes }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="font-mono text-xs font-bold text-purple-800 bg-purple-50 px-2 py-1 rounded border border-purple-200">
                                            {{ number_format($p->hours, 1) }} hrs
                                        </span>
                                        @can('volunteers.participation.edit')
                                            <button type="button" wire:click="openEditParticipation({{ $p->id }})" class="text-slate-400 hover:text-slate-700">
                                                <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endcan
                                        @can('volunteers.participation.delete')
                                            <button type="button" wire:click="deleteParticipation({{ $p->id }})" class="text-slate-400 hover:text-rose-600">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-xs text-slate-400 bg-slate-50/50">
                                    No service activities logged yet. Click "Log Service Hours" to record community participation.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Audit History -->
                    @if(count($viewVolunteerAuditLogs) > 0)
                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Volunteer Audit Trail</h4>
                            <div class="space-y-2">
                                @foreach($viewVolunteerAuditLogs as $log)
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
                    <button type="button" wire:click="$set('showViewModal', false)" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                        Close Profile
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- APPROVE VOLUNTEER MODAL -->
    <!-- ========================================================================= -->
    @if($showApprovalModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-fade-in" @click.away="$wire.showApprovalModal = false">
                <div class="p-6 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                        <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 font-display">Approve Volunteer Application</h3>
                        <p class="text-xs text-slate-600 mt-1">
                            Are you sure you want to approve volunteer application <span class="font-mono font-bold text-emerald-800">{{ $approvalVolunteerCode }}</span> for <span class="font-bold text-slate-900">{{ $approvalMemberName }}</span>?
                        </p>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showApprovalModal', false)" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="button" wire:click="approveVolunteer" class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-emerald-700 hover:bg-emerald-800">
                            Confirm Approval
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- REJECT VOLUNTEER MODAL (Requires Reason) -->
    <!-- ========================================================================= -->
    @if($showRejectionModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-fade-in" @click.away="$wire.showRejectionModal = false">
                <div class="p-6 space-y-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900 font-display">Reject Volunteer Application</h3>
                        <p class="text-xs text-slate-600 mt-1">
                            Rejecting volunteer application for <span class="font-bold text-slate-900">{{ $rejectionMemberName }}</span> ({{ $rejectionVolunteerCode }}).
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Reason for Rejection <span class="text-rose-500">*</span></label>
                        <textarea wire:model="rejectionReason" rows="3" placeholder="State specific reason for audit and internal documentation..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-rose-600/20"></textarea>
                        @error('rejectionReason') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showRejectionModal', false)" class="px-4 py-2 rounded-xl text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="button" wire:click="rejectVolunteer" class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-rose-700 hover:bg-rose-800">
                            Confirm Rejection
                        </button>
                    </div>
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
                    <h3 class="text-sm font-bold text-slate-900">Change Volunteer Status</h3>
                    <button type="button" wire:click="$set('showStatusModal', false)" class="text-slate-400 hover:text-slate-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form wire:submit="updateStatus" class="p-6 space-y-4">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-0.5">
                        <p><span class="text-slate-500">Volunteer:</span> <span class="font-bold text-slate-900">{{ $statusMemberName }}</span></p>
                        <p><span class="text-slate-500">Code:</span> <span class="font-mono text-emerald-700 font-semibold">{{ $statusVolunteerCode }}</span></p>
                        <p><span class="text-slate-500">Current Status:</span> <span class="capitalize font-medium text-slate-800">{{ $oldStatus }}</span></p>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">New Status</label>
                        <select wire:model="newStatus" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            <option value="active">Active</option>
                            <option value="approved">Approved</option>
                            <option value="pending">Pending Review</option>
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
    <!-- LOG PARTICIPATION & SERVICE HOURS MODAL -->
    <!-- ========================================================================= -->
    @if($showParticipationModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden animate-fade-in" @click.away="$wire.showParticipationModal = false">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/80">
                    <h3 class="text-sm font-bold text-slate-900">
                        {{ $editingParticipationId ? 'Edit Service Activity Log' : 'Log Volunteer Service Hours' }}
                    </h3>
                    <button type="button" wire:click="$set('showParticipationModal', false)" class="text-slate-400 hover:text-slate-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <form wire:submit="saveParticipation" class="p-6 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Activity / Program Name <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="activityName" placeholder="e.g. Coimbatore Rural Health Camp" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                        @error('activityName') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Service Date <span class="text-rose-500">*</span></label>
                            <input type="date" wire:model="activityDate" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            @error('activityDate') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-slate-700 mb-1">Service Hours <span class="text-rose-500">*</span></label>
                            <input type="number" step="0.25" min="0.25" max="24" wire:model="activityHours" placeholder="4.0" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                            @error('activityHours') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Role / Contribution</label>
                        <input type="text" wire:model="activityRole" placeholder="e.g. Patient Registration & Logistics" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200">
                        @error('activityRole') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Activity Notes</label>
                        <textarea wire:model="activityNotes" rows="2" placeholder="Optional notes or feedback..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-200"></textarea>
                        @error('activityNotes') <span class="text-rose-600 text-xs block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showParticipationModal', false)" class="px-3.5 py-2 rounded-xl text-xs font-medium text-slate-700 bg-white border border-slate-200 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-semibold text-white bg-purple-700 hover:bg-purple-800">
                            Save Service Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
