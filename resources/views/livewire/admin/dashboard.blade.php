<div>
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">NGO Overview & Dashboard</h1>
            <p class="text-xs text-slate-500 mt-1">Welcome back, <span class="font-semibold text-emerald-700">{{ auth()->user()->name }}</span>. Here is the operational summary of the platform.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.website.homepage') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold shadow-md shadow-emerald-800/20 transition-all">
                <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                <span>Edit Homepage</span>
            </a>
            <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                <i data-lucide="external-link" class="w-3.5 h-3.5 text-slate-400"></i>
                <span>View Public Site</span>
            </a>
        </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <!-- Card 1: Members -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Community Members</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 font-heading">{{ number_format($totalMembers) }}</h3>
                <p class="text-[11px] text-emerald-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="check" class="w-3 h-3"></i>
                    <span>{{ $activeMembers }} Active ({{ $newMembersThisMonth }} new this month)</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center ring-4 ring-emerald-50/50">
                <i data-lucide="user-check" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 2: Volunteers -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Volunteers & Service</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 font-heading">{{ number_format($totalVolunteers) }}</h3>
                <p class="text-[11px] text-blue-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="clock" class="w-3 h-3"></i>
                    <span>{{ $activeVolunteers }} Active | {{ number_format($totalVolunteerHours, 1) }} Total Hrs</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center ring-4 ring-blue-50/50">
                <i data-lucide="heart-handshake" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 2: Users -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Platform Staff</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 font-heading">{{ $totalUsers }}</h3>
                <p class="text-[11px] text-emerald-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="shield" class="w-3 h-3"></i>
                    <span>{{ $activeUsers }} Active Accounts</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center ring-4 ring-teal-50/50">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 2: Homepage Sections -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Homepage CMS</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 font-heading">{{ $totalSections }} Sections</h3>
                <p class="text-[11px] text-teal-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="eye" class="w-3 h-3"></i>
                    <span>{{ $enabledSections }} Sections Published</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center ring-4 ring-teal-50/50">
                <i data-lucide="layout-template" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 3: Media Items -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Media Assets</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1 font-heading">{{ $totalMedia }}</h3>
                <p class="text-[11px] text-amber-600 font-medium mt-1 flex items-center gap-1">
                    <i data-lucide="hard-drive" class="w-3 h-3"></i>
                    <span>Local & Cloud Storage</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center ring-4 ring-amber-50/50">
                <i data-lucide="images" class="w-6 h-6"></i>
            </div>
        </div>

        <!-- Card 4: System State -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">Database State</p>
                <h3 class="text-base font-bold text-slate-900 mt-1 font-heading">MySQL 8.4 Connected</h3>
                <p class="text-[11px] text-emerald-600 font-medium mt-1 flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>All Tables Healthy</span>
                </p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center ring-4 ring-emerald-50/50">
                <i data-lucide="database" class="w-6 h-6"></i>
            </div>
        </div>
    </div>

    <!-- Main 2-Column Split: Recent Audit Activities & Quick Navigation -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left 2 Cols: Recent Audit Trail -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6">
            <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <i data-lucide="activity" class="w-4 h-4"></i>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900 font-heading">Recent Administrative Activity</h2>
                </div>
                <a href="{{ route('admin.security.audit-logs') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                    View All Logs &rarr;
                </a>
            </div>

            @if ($recentActivities->isEmpty())
                <div class="py-10 text-center text-slate-400 text-xs">
                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                    <p>No audit activity recorded yet.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs min-w-[480px]">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100">
                                <th class="pb-2.5 font-semibold">User</th>
                                <th class="pb-2.5 font-semibold">Action</th>
                                <th class="pb-2.5 font-semibold">Entity</th>
                                <th class="pb-2.5 font-semibold text-right">Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($recentActivities as $act)
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    <td class="py-3 font-medium text-slate-900">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px]">
                                                {{ strtoupper(substr($act->user->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <span>{{ $act->user->name ?? 'System / CLI' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 text-slate-700">{{ $act->action }}</td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-medium bg-slate-100 text-slate-700">
                                            {{ $act->entity_type ?: 'System' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right text-slate-500 font-mono text-[11px]">
                                        {{ $act->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Right 1 Col: Quick Links & Recent Logins -->
        <div class="space-y-6">
            
            <!-- Quick Management Hub -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Quick Navigation</h3>
                <div class="grid grid-cols-2 gap-2.5 text-xs">
                    <a href="{{ route('admin.website.homepage') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 border border-slate-100 flex flex-col items-center text-center gap-1.5 transition-colors">
                        <i data-lucide="layout-template" class="w-5 h-5 text-emerald-600"></i>
                        <span class="font-medium">Homepage CMS</span>
                    </a>
                    <a href="{{ route('admin.website.media') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 border border-slate-100 flex flex-col items-center text-center gap-1.5 transition-colors">
                        <i data-lucide="images" class="w-5 h-5 text-teal-600"></i>
                        <span class="font-medium">Media Library</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 border border-slate-100 flex flex-col items-center text-center gap-1.5 transition-colors">
                        <i data-lucide="users" class="w-5 h-5 text-emerald-700"></i>
                        <span class="font-medium">Staff Users</span>
                    </a>
                    <a href="{{ route('admin.roles') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:text-emerald-800 border border-slate-100 flex flex-col items-center text-center gap-1.5 transition-colors">
                        <i data-lucide="shield-check" class="w-5 h-5 text-amber-600"></i>
                        <span class="font-medium">Roles & RBAC</span>
                    </a>
                </div>
            </div>

            <!-- Recent Logins Stream -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Recent Logins</h3>
                    <a href="{{ route('admin.security.login-history') }}" class="text-[11px] text-emerald-700 hover:underline">Full History</a>
                </div>

                @if ($recentLogins->isEmpty())
                    <p class="text-xs text-slate-400 py-3 text-center">No logins recorded yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach ($recentLogins as $login)
                            <div class="flex items-center justify-between text-xs py-1 border-b border-slate-100 last:border-0">
                                <div>
                                    <p class="font-semibold text-slate-800">{{ $login->user->name ?? 'Unknown' }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono">{{ $login->ip_address ?: '127.0.0.1' }}</p>
                                </div>
                                <span class="text-[10px] text-slate-500">{{ $login->login_at->diffForHumans() }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
