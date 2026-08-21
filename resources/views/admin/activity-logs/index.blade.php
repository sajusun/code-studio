<x-layouts.admin>
    <div class="space-y-6">
        <!-- Header & Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-theme-main tracking-tight">System Activity Logs</h1>
                <p class="text-xs text-theme-muted mt-0.5">Audit trail and history of administrative operations, changes, and system events</p>
            </div>

            <!-- Search Filter Form -->
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search logs..." 
                           class="w-64 pl-9 pr-4 py-2 text-xs rounded-xl bg-theme-card border border-theme text-theme-main focus:outline-hidden focus:ring-2 focus:ring-theme-primary">
                    <svg class="w-4 h-4 text-theme-muted absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <button type="submit" class="px-4 py-2 bg-theme-primary text-white text-xs font-bold rounded-xl shadow-xs hover:bg-opacity-90 transition-all">
                    Filter
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.activity-logs.index') }}" class="px-3 py-2 bg-theme-main/10 text-theme-muted hover:text-theme-main text-xs font-bold rounded-xl transition-all">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Logs Table Card -->
        <x-card title="Audit Trail History">
            <x-table>
                <x-slot name="thead">
                    <x-table.th>ID</x-table.th>
                    <x-table.th>Log Name</x-table.th>
                    <x-table.th>Description / Action</x-table.th>
                    <x-table.th>Performed By</x-table.th>
                    <x-table.th>Timestamp</x-table.th>
                </x-slot>

                @forelse($logs as $log)
                    <tr class="hover:bg-theme-main/5 transition-colors">
                        <x-table.td class="font-mono text-xs text-theme-muted">#{{ $log->id }}</x-table.td>
                        <x-table.td>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-theme-primary/10 text-theme-primary">
                                {{ $log->log_name ?? 'default' }}
                            </span>
                        </x-table.td>
                        <x-table.td class="font-medium text-theme-main">
                            {{ $log->description }}
                        </x-table.td>
                        <x-table.td>
                            @if($log->causer)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-theme-primary flex items-center justify-center font-bold text-white text-[10px]">
                                        {{ strtoupper(substr($log->causer->name, 0, 1)) }}
                                    </div>
                                    <span class="text-xs font-semibold text-theme-main">{{ $log->causer->name }}</span>
                                </div>
                            @else
                                <span class="text-xs text-theme-muted italic">System Automated</span>
                            @endif
                        </x-table.td>
                        <x-table.td class="text-xs text-theme-muted">
                            {{ $log->created_at ? $log->created_at->format('M d, Y • h:i A') : 'N/A' }}
                        </x-table.td>
                    </tr>
                @empty
                    <tr>
                        <x-table.td colspan="5" class="text-center py-8 text-theme-muted text-xs">
                            No activity log entries recorded yet.
                        </x-table.td>
                    </tr>
                @endforelse
            </x-table>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        </x-card>
    </div>
</x-layouts.admin>
