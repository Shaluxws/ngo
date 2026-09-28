<div class="space-y-6 text-xs">
    <!-- Section: Overview -->
    <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Main</div>
        <ul class="space-y-1">
            <li>
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @can('members.view')
            <li>
                <a href="{{ route('admin.members.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.members*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="user-check" class="w-4 h-4 {{ request()->routeIs('admin.members*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Member Management</span>
                </a>
            </li>
            @endcan
            @can('volunteers.view')
            <li>
                <a href="{{ route('admin.volunteers.index') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.volunteers*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="heart-handshake" class="w-4 h-4 {{ request()->routeIs('admin.volunteers*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Volunteer Management</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>

    <!-- Section: Website & CMS -->
    @canany(['website.view', 'homepage.view', 'theme.view', 'media.view'])
    <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Website CMS</div>
        <ul class="space-y-1">
            @can('homepage.view')
            <li>
                <a href="{{ route('admin.website.homepage') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.website.homepage*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="layout-template" class="w-4 h-4 {{ request()->routeIs('admin.website.homepage*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Homepage CMS</span>
                </a>
            </li>
            @endcan

            @can('media.view')
            <li>
                <a href="{{ route('admin.website.media') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.website.media*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="images" class="w-4 h-4 {{ request()->routeIs('admin.website.media*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Media Library</span>
                </a>
            </li>
            @endcan

            @can('theme.view')
            <li>
                <a href="{{ route('admin.website.theme') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.website.theme*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="palette" class="w-4 h-4 {{ request()->routeIs('admin.website.theme*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Theme Editor</span>
                </a>
            </li>
            @endcan

            @can('website.view')
            <li>
                <a href="{{ route('admin.website.settings') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.website.settings*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="sliders-horizontal" class="w-4 h-4 {{ request()->routeIs('admin.website.settings*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>General & SEO</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @endcanany

    <!-- Section: People & Access Control -->
    @canany(['users.view', 'roles.view'])
    <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Access Control & Staff</div>
        <ul class="space-y-1">
            @can('users.view')
            <li>
                <a href="{{ route('admin.users') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.users*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="users" class="w-4 h-4 {{ request()->routeIs('admin.users*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Users & Staff</span>
                </a>
            </li>
            @endcan

            @can('roles.view')
            <li>
                <a href="{{ route('admin.roles') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.roles*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="shield-check" class="w-4 h-4 {{ request()->routeIs('admin.roles*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Roles & Permissions</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @endcanany

    <!-- Section: Security & Logs -->
    @canany(['audit_logs.view', 'login_histories.view'])
    <div>
        <div class="px-3 mb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400">Security & Logs</div>
        <ul class="space-y-1">
            @can('audit_logs.view')
            <li>
                <a href="{{ route('admin.security.audit-logs') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.security.audit-logs*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="file-text" class="w-4 h-4 {{ request()->routeIs('admin.security.audit-logs*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Audit Logs</span>
                </a>
            </li>
            @endcan

            @can('login_histories.view')
            <li>
                <a href="{{ route('admin.security.login-history') }}" 
                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium transition-all {{ request()->routeIs('admin.security.login-history*') ? 'bg-emerald-600 text-white shadow-md shadow-emerald-950/40' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i data-lucide="history" class="w-4 h-4 {{ request()->routeIs('admin.security.login-history*') ? 'text-white' : 'text-emerald-400' }}"></i>
                    <span>Login History</span>
                </a>
            </li>
            @endcan
        </ul>
    </div>
    @endcanany
</div>
