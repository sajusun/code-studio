<x-layouts.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-theme-main tracking-tight">Role & Permission Management</h2>
                <p class="text-xs text-theme-muted">Configure access roles, permissions matrix, and security policies</p>
            </div>

            <!-- Create Role Trigger Modal -->
            <button @click="$dispatch('open-modal', 'create-role-modal')" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl bg-theme-primary text-white shadow-xs hover:bg-theme-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create New Role
            </button>
        </div>

        <!-- Alert messages -->
        @if(session('success'))
            <div class="p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-600 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        <!-- Roles Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($roles as $role)
                <x-card title="{{ ucfirst($role->name) }}" subtitle="{{ $role->users_count }} assigned user(s)">
                    <x-slot name="actions">
                        <div class="flex items-center gap-1">
                            <button @click="$dispatch('open-modal', 'edit-role-{{ $role->id }}')" type="button" class="p-1.5 rounded-lg text-theme-muted hover:text-indigo-600 hover:bg-theme-main transition-colors" title="Edit Role">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>

                            @if($role->name !== 'admin')
                                <button type="button" onclick="confirmDelete('{{ route('admin.roles.destroy', $role->id) }}')" class="p-1.5 rounded-lg text-theme-muted hover:text-rose-600 hover:bg-theme-main transition-colors" title="Delete Role">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            @endif
                        </div>
                    </x-slot>

                    <div class="space-y-3">
                        <div class="text-xs font-bold text-theme-muted uppercase tracking-wider">Granted Permissions ({{ $role->permissions->count() }})</div>
                        
                        <div class="flex flex-wrap gap-1.5 max-h-36 overflow-y-auto pr-1">
                            @forelse($role->permissions as $perm)
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-theme-main border border-theme text-theme-main">
                                    {{ $perm->name }}
                                </span>
                            @empty
                                <span class="text-xs text-theme-muted">No direct permissions assigned.</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Edit Role Modal -->
                    <x-modal id="edit-role-{{ $role->id }}" title="Edit Role: {{ ucfirst($role->name) }}">
                        <form method="POST" action="{{ route('admin.roles.update', $role->id) }}" class="space-y-4 text-left">
                            @csrf
                            @method('PUT')

                            <div>
                                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Role Name</label>
                                <input type="text" name="name" value="{{ $role->name }}" {{ $role->name === 'admin' ? 'readonly' : 'required' }} class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
                            </div>

                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-theme-muted uppercase">Permissions Matrix</label>
                                <div class="max-h-60 overflow-y-auto space-y-3 pr-2">
                                    @foreach($permissions as $group => $groupPerms)
                                        <div class="p-3 rounded-xl bg-theme-main border border-theme">
                                            <h5 class="text-xs font-bold uppercase text-theme-primary mb-2">{{ ucfirst($group) }}</h5>
                                            <div class="grid grid-cols-2 gap-2">
                                                @foreach($groupPerms as $perm)
                                                    <label class="flex items-center gap-2 cursor-pointer text-xs">
                                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ $role->hasPermissionTo($perm->name) ? 'checked' : '' }} class="rounded border-theme text-theme-primary">
                                                        <span class="text-theme-main">{{ $perm->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-3 flex justify-end gap-2">
                                <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-xl bg-theme-primary text-white">Save Changes</button>
                            </div>
                        </form>
                    </x-modal>
                </x-card>
            @endforeach
        </div>
    </div>

    <!-- Create Role Modal -->
    <x-modal id="create-role-modal" title="Create New Access Role">
        <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Role Name</label>
                <input type="text" name="name" required placeholder="e.g. editor, moderator" class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
            </div>

            <div class="space-y-3">
                <label class="block text-xs font-bold text-theme-muted uppercase">Assign Permissions Matrix</label>
                <div class="max-h-60 overflow-y-auto space-y-3 pr-2">
                    @foreach($permissions as $group => $groupPerms)
                        <div class="p-3 rounded-xl bg-theme-main border border-theme">
                            <h5 class="text-xs font-bold uppercase text-theme-primary mb-2">{{ ucfirst($group) }}</h5>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($groupPerms as $perm)
                                    <label class="flex items-center gap-2 cursor-pointer text-xs">
                                        <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="rounded border-theme text-theme-primary">
                                        <span class="text-theme-main">{{ $perm->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-xl bg-theme-primary text-white">Create Role</button>
            </div>
        </form>
    </x-modal>
</x-layouts.admin>
