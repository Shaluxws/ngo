<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VolunteerProfile;

class VolunteerPolicy
{
    /**
     * Super Admin bypasses all checks.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('Super Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('volunteers.view');
    }

    public function view(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.view');
    }

    public function create(User $user): bool
    {
        return $user->can('volunteers.create');
    }

    public function update(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.edit');
    }

    public function delete(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.delete');
    }

    public function restore(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.restore') || $user->can('volunteers.delete');
    }

    public function forceDelete(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function approve(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.approve');
    }

    public function reject(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.reject');
    }

    public function changeStatus(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.suspend') || $user->can('volunteers.edit');
    }

    public function export(User $user): bool
    {
        return $user->can('volunteers.export') || $user->can('volunteers.view');
    }

    public function addParticipation(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.participation.create') || $user->can('volunteers.edit');
    }

    public function editParticipation(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.participation.edit') || $user->can('volunteers.edit');
    }

    public function deleteParticipation(User $user, VolunteerProfile $volunteer): bool
    {
        return $user->can('volunteers.participation.delete') || $user->can('volunteers.delete');
    }
}
