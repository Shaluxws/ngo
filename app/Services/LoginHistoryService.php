<?php

namespace App\Services;

use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class LoginHistoryService
{
    public static function recordLogin(User $user, string $status = 'successful'): ?LoginHistory
    {
        try {
            $user->update(['last_login_at' => now()]);

            return LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => Request::ip(),
                'user_agent' => substr(Request::userAgent() ?? '', 0, 500),
                'login_at' => now(),
                'status' => $status,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }

    public static function recordLogout(User $user): void
    {
        try {
            $lastLogin = LoginHistory::where('user_id', $user->id)
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first();

            if ($lastLogin) {
                $lastLogin->update(['logout_at' => now()]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
