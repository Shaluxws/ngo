<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Services\AuditService;
use App\Services\SuperAdminProtectionService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class UserManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $roleFilter = '';
    public string $statusFilter = '';

    // Modal state
    public bool $showUserModal = false;
    public ?int $userIdBeingEdited = null;

    // Form inputs
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $status = 'active';
    public array $selectedRoles = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userIdBeingEdited),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => $this->userIdBeingEdited ? 'nullable|min:8|confirmed' : 'required|min:8|confirmed',
            'status' => 'required|in:active,inactive,suspended',
            'selectedRoles' => 'array',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRoleFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->resetValidation();
        $this->resetForm();
        $this->userIdBeingEdited = null;
        $this->showUserModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->resetValidation();
        $user = User::with('roles')->findOrFail($id);
        $this->userIdBeingEdited = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->status = $user->status;
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->showUserModal = true;
    }

    public function saveUser(): void
    {
        $this->validate();

        if ($this->userIdBeingEdited) {
            $user = User::findOrFail($this->userIdBeingEdited);

            // Last Super Admin Protection check for status change
            if ($user->status === 'active' && $this->status !== 'active') {
                if (!SuperAdminProtectionService::canDeactivateUser($user)) {
                    session()->flash('error', 'Action rejected: You cannot deactivate or suspend the only active Super Admin.');
                    return;
                }
            }

            // Last Super Admin Protection check for removing Super Admin role
            if ($user->hasRole('Super Admin') && !in_array('Super Admin', $this->selectedRoles, true)) {
                if (!SuperAdminProtectionService::canRemoveSuperAdminRole($user)) {
                    session()->flash('error', 'Action rejected: You cannot remove the Super Admin role from the last active Super Admin.');
                    return;
                }
            }

            $oldData = $user->toArray();
            $oldRoles = $user->roles->pluck('name')->toArray();

            $user->name = $this->name;
            $user->email = $this->email;
            $user->phone = $this->phone ?: null;
            $user->status = $this->status;

            if (!empty($this->password)) {
                $user->password = Hash::make($this->password);
            }

            $user->save();
            $user->syncRoles($this->selectedRoles);

            AuditService::log(
                'Updated user account and roles',
                'users',
                $user->id,
                ['user' => $oldData, 'roles' => $oldRoles],
                ['user' => $user->toArray(), 'roles' => $this->selectedRoles]
            );

            session()->flash('success', "User '{$user->name}' updated successfully.");
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone ?: null,
                'password' => Hash::make($this->password),
                'status' => $this->status,
                'email_verified_at' => now(),
            ]);

            if (!empty($this->selectedRoles)) {
                $user->syncRoles($this->selectedRoles);
            }

            AuditService::log(
                'Created new user account',
                'users',
                $user->id,
                null,
                ['user' => $user->toArray(), 'roles' => $this->selectedRoles]
            );

            session()->flash('success', "New user '{$user->name}' created successfully.");
        }

        $this->showUserModal = false;
        $this->resetForm();
    }

    public function toggleStatus(int $userId): void
    {
        $user = User::findOrFail($userId);

        if ($user->status === 'active') {
            if (!SuperAdminProtectionService::canDeactivateUser($user)) {
                session()->flash('error', 'Cannot deactivate the last active Super Admin account.');
                return;
            }
            $user->status = 'inactive';
        } else {
            $user->status = 'active';
        }

        $user->save();

        AuditService::log(
            "Toggled user status to {$user->status}",
            'users',
            $user->id,
            null,
            ['status' => $user->status]
        );

        session()->flash('success', "User status updated to {$user->status}.");
    }

    public function deleteUser(int $userId): void
    {
        $user = User::findOrFail($userId);

        if (!SuperAdminProtectionService::canDeleteUser($user)) {
            session()->flash('error', 'Security Violation: Cannot delete the last active Super Admin account.');
            return;
        }

        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete your own currently logged-in account.');
            return;
        }

        $userData = $user->toArray();
        $user->delete();

        AuditService::log(
            'Deleted user account',
            'users',
            $userId,
            $userData,
            null
        );

        session()->flash('success', 'User account deleted successfully.');
    }

    protected function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->status = 'active';
        $this->selectedRoles = [];
        $this->userIdBeingEdited = null;
    }

    public function render()
    {
        $query = User::with('roles')->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            });
        }

        if (!empty($this->roleFilter)) {
            $query->role($this->roleFilter);
        }

        if (!empty($this->statusFilter)) {
            $query->where('status', $this->statusFilter);
        }

        $users = $query->paginate(10);
        $allRoles = Role::all();

        return view('livewire.admin.user-management', [
            'users' => $users,
            'allRoles' => $allRoles,
        ]);
    }
}
