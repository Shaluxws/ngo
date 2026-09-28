<?php

namespace App\Http\Middleware;

use App\Services\LoginHistoryService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if (!$user->isActive()) {
                LoginHistoryService::recordLogout($user);
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'email' => 'Your account is inactive or suspended. Please contact administrator.',
                ]);
            }
        }

        return $next($request);
    }
}
