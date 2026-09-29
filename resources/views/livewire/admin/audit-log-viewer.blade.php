<div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Security & Audit Logs</h1>
            <p class="text-xs text-slate-500 mt-1">Immutable chronological records of administrative modifications, role adjustments, CMS changes, and security events.</p>
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
                       placeholder="Search actions, IP addresses..." 
                       class="block w-full rounded-xl pl-10 pr-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 text-slate-900">
            </div>

            <div>
                <select wire:model.live="userFilter" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-700">
                    <option value="">All Actors / Users</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="entityFilter" class="block w-full rounded-xl px-3.5 py-2 text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:border-emerald-600 text-slate-700">
                    <option value="">All Entity Types</option>
                    @foreach ($entityTypes as $ent)
                        <option value="{{ $ent }}">{{ $ent }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs min-w-[640px]">
                <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-semibold border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-4">Timestamp</th>
                        <th class="py-3.5 px-4">Actor / User</th>
                        <th class="py-3.5 px-4">Action Taken</th>
                        <th class="py-3.5 px-4">Entity</th>
                        <th class="py-3.5 px-4">IP Address</th>
                        <th class="py-3.5 px-4 text-right">Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                {{ $log->created_at->format('Y-m-d H:i:s') }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-semibold text-slate-900">{{ $log->user->name ?? 'System / CLI' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $log->user->email ?? 'Automated' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-800 font-medium">
                                {{ $log->action }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if ($log->entity_type)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-100 text-slate-700">
                                        {{ $log->entity_type }} #{{ $log->entity_id }}
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[10px]">—</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                                {{ $log->ip_address ?: '127.0.0.1' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                @if ($log->old_values || $log->new_values)
                                    <button type="button" 
                                            wire:click="inspectLog({{ $log->id }})" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-emerald-700 hover:bg-emerald-50 transition-colors">
                                        <i data-lucide="code" class="w-3.5 h-3.5"></i>
                                        <span>Diff</span>
                                    </button>
                                @else
                                    <span class="text-slate-300 text-[10px]">No diff</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <i data-lucide="file-text" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                <p>No audit logs found matching criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- JSON Diff Inspection Modal -->
    @if ($showDetailModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs">
            <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl border border-slate-100 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div>
                        <h3 class="text-base font-bold text-slate-900 font-heading">Audit Record Details</h3>
                        <p class="text-xs text-slate-500">{{ $selectedLog?->action }}</p>
                    </div>
                    <button type="button" wire:click="$set('showDetailModal', false)" class="text-slate-400 hover:text-slate-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                @if ($selectedLog)
                    <div class="mt-4 space-y-4 text-xs">
                        <div class="grid grid-cols-2 gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <div><span class="text-slate-400">Actor:</span> <span class="font-semibold">{{ $selectedLog->user->name ?? 'System' }}</span></div>
                            <div><span class="text-slate-400">Time:</span> <span class="font-mono">{{ $selectedLog->created_at->format('Y-m-d H:i:s') }}</span></div>
                            <div><span class="text-slate-400">IP Address:</span> <span class="font-mono">{{ $selectedLog->ip_address ?: '127.0.0.1' }}</span></div>
                            <div><span class="text-slate-400">Entity:</span> <span class="font-mono">{{ $selectedLog->entity_type }} #{{ $selectedLog->entity_id }}</span></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">Previous State (Old Values)</h4>
                                <pre class="bg-slate-900 text-emerald-400 p-3 rounded-xl overflow-x-auto text-[11px] font-mono h-56">{{ json_encode($selectedLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">New State (Modified Values)</h4>
                                <pre class="bg-slate-900 text-teal-300 p-3 rounded-xl overflow-x-auto text-[11px] font-mono h-56">{{ json_encode($selectedLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                    <button type="button" wire:click="$set('showDetailModal', false)" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
