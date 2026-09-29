<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Users & Staff Management</h1>
            <p class="text-xs text-slate-500 mt-1">Manage portal accounts, assign RBAC roles, and control active status.</p>
        </div>
        <button type="button" 
                wire:click="openCreateModal" 
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20 transition-all">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            <span>Add New User</span>
        </button>
    </div>

    <!-- Filters and Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search by name, email, phone..." 
                       class="block w-full rounded-xl pl-10 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900">
            </div>

            <div>
                <select wire:model.live="roleFilter" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-700">
                    <option value="">All Roles</option>
                    @foreach ($allRoles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="statusFilter" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-700">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[600px]">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">User</th>
                        <th class="py-3.5 px-4">Roles</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Last Login</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs ring-2 ring-emerald-200/60">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-900 flex items-center gap-2">
                                            <span>{{ $user->name }}</span>
                                            @if ($user->id === auth()->id())
                                                <span class="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.2 rounded">You</span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-500">{{ $user->email }} {{ $user->phone ? '• ' . $user->phone : '' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse ($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold {{ $role->name === 'Super Admin' ? 'bg-amber-100 text-amber-800 ring-1 ring-amber-300/60' : 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200' }}">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-[10px] text-slate-400">No role</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($user->status === 'active')
                                    <button type="button" 
                                            wire:click="toggleStatus({{ $user->id }})" 
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Active</span>
                                    </button>
                                @elseif ($user->status === 'inactive')
                                    <button type="button" 
                                            wire:click="toggleStatus({{ $user->id }})" 
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-100 text-slate-600 border border-slate-200 hover:bg-slate-200 transition-colors">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        <span>Inactive</span>
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        <span>Suspended</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 text-[11px] font-mono">
                                {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button type="button" 
                                            wire:click="openEditModal({{ $user->id }})" 
                                            class="p-1.5 rounded-lg text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 transition-colors"
                                            title="Edit user">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <button type="button" 
                                            @click="openConfirm('Delete User Account', 'Are you sure you want to delete user \'{{ $user->name }}\'? This action cannot be undone.', () => $wire.deleteUser({{ $user->id }}))"
                                            class="p-1.5 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                            title="Delete user">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <i data-lucide="users" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p>No users found matching your filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Create / Edit User Modal -->
    @if ($showUserModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900 font-heading">
                        {{ $userIdBeingEdited ? 'Edit User Account' : 'Create New User' }}
                    </h3>
                    <button type="button" wire:click="$set('showUserModal', false)" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <form wire:submit.prevent="saveUser" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        @error('name') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" wire:model="email" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        @error('email') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Phone Number</label>
                        <input type="text" wire:model="phone" placeholder="+91 94420 12345" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        @error('phone') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">
                                Password {{ $userIdBeingEdited ? '(Leave blank to keep current)' : '*' }}
                            </label>
                            <input type="password" wire:model="password" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            @error('password') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 uppercase">Confirm Password</label>
                            <input type="password" wire:model="password_confirmation" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase">Account Status <span class="text-red-500">*</span></label>
                        <select wire:model="status" class="mt-1 block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-900">
                            <option value="active">Active (Access Allowed)</option>
                            <option value="inactive">Inactive (Access Blocked)</option>
                            <option value="suspended">Suspended (Access Revoked)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-2">Assign Roles</label>
                        <div class="space-y-2 p-3 bg-slate-50 rounded-xl border border-slate-200 max-h-36 overflow-y-auto">
                            @foreach ($allRoles as $role)
                                <label class="flex items-center gap-2 text-xs text-slate-800 cursor-pointer">
                                    <input type="checkbox" 
                                           wire:model="selectedRoles" 
                                           value="{{ $role->name }}" 
                                           class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600">
                                    <span class="font-medium">{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button type="button" wire:click="$set('showUserModal', false)" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20">
                            Save User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
