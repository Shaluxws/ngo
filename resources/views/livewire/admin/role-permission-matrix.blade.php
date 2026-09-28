<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Roles & Granular Permission Matrix</h1>
            <p class="text-xs text-slate-500 mt-1">Configure role-based access control (RBAC) powered by Spatie Laravel Permission.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- Left 1 Col: Roles List & Create Role -->
        <div class="space-y-5">
            <!-- Create Custom Role Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Create New Role</h3>
                <form wire:submit.prevent="createRole" class="space-y-2">
                    <input type="text" 
                           wire:model="newRoleName" 
                           placeholder="e.g. Field Coordinator" 
                           class="block w-full rounded-xl px-3 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                    @error('newRoleName') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    
                    <button type="submit" class="w-full py-2 px-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                        <span>Add Role</span>
                    </button>
                </form>
            </div>

            <!-- Roles List -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 px-3 py-2">System Roles</h3>
                <div class="space-y-1">
                    @foreach ($roles as $r)
                        <div class="flex items-center justify-between rounded-xl p-2.5 transition-colors {{ $selectedRoleId === $r->id ? 'bg-emerald-50 text-emerald-900 font-semibold ring-1 ring-emerald-200' : 'text-slate-700 hover:bg-slate-50' }}">
                            <button type="button" 
                                    wire:click="selectRole({{ $r->id }})" 
                                    class="flex-1 text-left flex items-center justify-between pr-2">
                                <span class="text-xs">{{ $r->name }}</span>
                                <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $selectedRoleId === $r->id ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $r->users_count }} users
                                </span>
                            </button>

                            @if (!in_array($r->name, ['Super Admin', 'Admin', 'Overall Leader', 'Community Leader', 'Volunteer', 'Member']))
                                <button type="button" 
                                        @click="openConfirm('Delete Custom Role', 'Are you sure you want to delete role \'{{ $r->name }}\'?', () => $wire.deleteRole({{ $r->id }}))"
                                        class="text-slate-400 hover:text-red-600 p-1"
                                        title="Delete custom role">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right 3 Cols: Granular Permission Matrix -->
        <div class="lg:col-span-3">
            @if ($selectedRole)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 mb-6 border-b border-slate-100 gap-2">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs uppercase font-semibold text-slate-400 tracking-wider">Configuring Permissions for:</span>
                                <span class="px-2.5 py-0.5 rounded-md text-xs font-bold {{ $selectedRole->name === 'Super Admin' ? 'bg-amber-100 text-amber-900 ring-1 ring-amber-300' : 'bg-emerald-100 text-emerald-900' }}">
                                    {{ $selectedRole->name }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-1">
                                {{ $selectedRole->name === 'Super Admin' ? 'Super Admin has full immutable access to all backend operations and modules.' : 'Toggle permissions below. Changes immediately sync to MySQL database and apply to users with this role.' }}
                            </p>
                        </div>
                        <span class="text-xs font-mono text-slate-400 self-start sm:self-auto">
                            {{ count($rolePermissions) }} active permissions
                        </span>
                    </div>

                    <div class="space-y-6">
                        @foreach ($permissionGroups as $groupTitle => $perms)
                            <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4">
                                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-700 mb-3 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    <span>{{ $groupTitle }}</span>
                                </h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach ($perms as $permKey => $permLabel)
                                        @php
                                            $isGranted = in_array($permKey, $rolePermissions, true);
                                            $isSuperAdmin = $selectedRole->name === 'Super Admin';
                                        @endphp
                                        <label class="flex items-start gap-2.5 p-2 rounded-lg bg-white border border-slate-200/80 hover:border-emerald-300 transition-colors {{ $isSuperAdmin ? 'opacity-90 cursor-not-allowed' : 'cursor-pointer' }}">
                                            <input type="checkbox" 
                                                   {{ $isGranted || $isSuperAdmin ? 'checked' : '' }}
                                                   {{ $isSuperAdmin ? 'disabled' : '' }}
                                                   wire:click="togglePermission('{{ $permKey }}')"
                                                   class="mt-0.5 h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600 disabled:opacity-60">
                                            <div class="text-xs">
                                                <span class="font-medium text-slate-900 block">{{ $permLabel }}</span>
                                                <span class="text-[10px] font-mono text-slate-400">{{ $permKey }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            @endif
        </div>

    </div>
</div>
