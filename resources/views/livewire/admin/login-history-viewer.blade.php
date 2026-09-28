<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">User Login & Access History</h1>
            <p class="text-xs text-slate-500 mt-1">Audit trail of successful authentications, failed attempts, and active session durations.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <i data-lucide="search" class="w-4 h-4"></i>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Filter by IP address..." 
                       class="block w-full rounded-xl pl-10 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900">
            </div>

            <div>
                <select wire:model.live="userFilter" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-700">
                    <option value="">All Users</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="statusFilter" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-700">
                    <option value="">All Outcomes</option>
                    <option value="successful">Successful</option>
                    <option value="failed_bad_credentials">Failed (Credentials)</option>
                    <option value="failed_inactive">Failed (Inactive Account)</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">User</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">IP Address</th>
                        <th class="py-3.5 px-4">Login Time</th>
                        <th class="py-3.5 px-4">Logout Time</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($histories as $h)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">{{ $h->user->name ?? 'Unknown / Deleted' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $h->user->email ?? '' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($h->status === 'successful')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Success</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-red-50 text-red-700 border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        <span>{{ ucfirst(str_replace('_', ' ', $h->status)) }}</span>
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                                {{ $h->ip_address ?: '127.0.0.1' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                                {{ $h->login_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                {{ $h->logout_at ? $h->logout_at->format('Y-m-d H:i:s') : '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <i data-lucide="history" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p>No login history found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-slate-100">
            {{ $histories->links() }}
        </div>
    </div>
</div>
