<?php

namespace App\Policies;

use App\Models\Member;
use App\Models\User;

class MemberPolicy
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
        return $user->can('members.view');
    }

    public function view(User $user, Member $member): bool
    {
        return $user->can('members.view');
    }

    public function create(User $user): bool
    {
        return $user->can('members.create');
    }

    public function update(User $user, Member $member): bool
    {
        return $user->can('members.edit');
    }

    public function delete(User $user, Member $member): bool
    {
        return $user->can('members.delete');
    }

    public function restore(User $user, Member $member): bool
    {
        return $user->can('members.restore') || $user->can('members.delete');
    }

    public function forceDelete(User $user, Member $member): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function export(User $user): bool
    {
        return $user->can('members.export') || $user->can('members.view');
    }

    public function changeStatus(User $user, Member $member): bool
    {
        return $user->can('members.suspend') || $user->can('members.edit');
    }
}
