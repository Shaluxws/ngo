<?php

namespace App\Livewire\Admin;

use App\Models\AuditLog;
use App\Models\HomepageSection;
use App\Models\LoginHistory;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $totalSections = HomepageSection::count();
        $enabledSections = HomepageSection::where('is_enabled', true)->count();
        $totalMembers = \App\Models\Member::count();
        $activeMembers = \App\Models\Member::where('status', 'active')->count();
        $pendingMembers = \App\Models\Member::where('status', 'pending')->count();
        $newMembersThisMonth = \App\Models\Member::whereMonth('joined_at', now()->month)
            ->whereYear('joined_at', now()->year)
            ->count();

        $totalVolunteers = \App\Models\VolunteerProfile::count();
        $activeVolunteers = \App\Models\VolunteerProfile::where('volunteer_status', 'active')->count();
        $pendingVolunteers = \App\Models\VolunteerProfile::where('volunteer_status', 'pending')->count();
        $volunteersThisMonth = \App\Models\VolunteerProfile::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $totalVolunteerHours = \App\Models\VolunteerParticipation::sum('hours') ?? 0;

        $recentActivities = AuditLog::with('user')
            ->latest('created_at')
            ->take(8)
            ->get();

        $recentLogins = LoginHistory::with('user')
            ->latest('login_at')
            ->take(6)
            ->get();

        return view('livewire.admin.dashboard', [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalSections' => $totalSections,
            'enabledSections' => $enabledSections,
            'totalMedia' => Media::count(),
            'totalMembers' => $totalMembers,
            'activeMembers' => $activeMembers,
            'pendingMembers' => $pendingMembers,
            'newMembersThisMonth' => $newMembersThisMonth,
            'totalVolunteers' => $totalVolunteers,
            'activeVolunteers' => $activeVolunteers,
            'pendingVolunteers' => $pendingVolunteers,
            'volunteersThisMonth' => $volunteersThisMonth,
            'totalVolunteerHours' => $totalVolunteerHours,
            'recentActivities' => $recentActivities,
            'recentLogins' => $recentLogins,
        ]);
    }
}
