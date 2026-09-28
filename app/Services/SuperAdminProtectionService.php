<?php

namespace App\Services;

use App\Models\User;

class SuperAdminProtectionService
{
    /**
     * Get count of active Super Admins
     */
    public static function getActiveSuperAdminCount(): int
    {
        return User::role('Super Admin')
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Check if a user is an active Super Admin
     */
    public static function isActiveSuperAdmin(User $user): bool
    {
        return $user->hasRole('Super Admin') && $user->status === 'active' && is_null($user->deleted_at);
    }

    /**
     * Check if deleting this user would leave zero active Super Admins
     */
    public static function canDeleteUser(User $user): bool
    {
        if (!self::isActiveSuperAdmin($user)) {
            return true;
        }

        return self::getActiveSuperAdminCount() > 1;
    }

    /**
     * Check if deactivating/suspending this user would leave zero active Super Admins
     */
    public static function canDeactivateUser(User $user): bool
    {
        if (!self::isActiveSuperAdmin($user)) {
            return true;
        }

        return self::getActiveSuperAdminCount() > 1;
    }

    /**
     * Check if removing the Super Admin role from this user would leave zero active Super Admins
     */
    public static function canRemoveSuperAdminRole(User $user): bool
    {
        if (!self::isActiveSuperAdmin($user)) {
            return true;
        }

        return self::getActiveSuperAdminCount() > 1;
    }
}
