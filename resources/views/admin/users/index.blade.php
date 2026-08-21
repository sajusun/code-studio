<x-layouts.admin>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-theme-main tracking-tight">User & Admin Staff Management</h2>
                <p class="text-xs text-theme-muted">Manage system users, staff accounts, avatars, and role assignments</p>
            </div>

            <!-- Create User Trigger Modal -->
            <button @click="$dispatch('open-modal', 'create-user-modal')" type="button" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-xl bg-theme-primary text-white shadow-xs hover:bg-theme-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Staff / User
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

        <!-- Filter & Search Bar -->
        <x-card>
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap items-center gap-4">
                <div class="flex-1 min-w-[240px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full px-4 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:ring-2 focus:ring-theme-primary">
                </div>

                <div class="w-48">
                    <select name="role" class="w-full px-4 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main focus:outline-hidden focus:ring-2 focus:ring-theme-primary">
                        <option value="">All Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-xl bg-theme-main border border-theme text-theme-main hover:bg-theme-card transition-colors">
                    Filter
                </button>

                @if(request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </x-card>

        <!-- Users Table -->
        <x-card title="Registered Users" subtitle="Showing paginated list of accounts">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="text-xs uppercase text-theme-muted bg-theme-main border-b border-theme">
                        <tr>
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Email</th>
                            <th class="p-3.5">Roles</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5">Joined Date</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-theme">
                        @forelse($users as $user)
                            <tr class="hover:bg-theme-main/50 transition-colors">
                                <td class="p-3.5">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $user->image }}" class="w-9 h-9 rounded-full border border-theme object-cover shadow-xs" alt="{{ $user->name }}">
                                        <div>
                                            <p class="font-bold text-theme-main leading-tight">{{ $user->name }}</p>
                                            <p class="text-xs text-theme-muted">ID #{{ $user->id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5 font-medium text-theme-main">{{ $user->email }}</td>
                                <td class="p-3.5">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($user->roles as $r)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-theme-primary/10 text-theme-primary border border-theme-primary/20">
                                                {{ ucfirst($r->name) }}
                                            </span>
                                        @empty
                                            <span class="text-xs text-theme-muted">No Role</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $user->status === 'active' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                                        {{ ucfirst($user->status ?? 'active') }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-theme-muted text-xs">{{ $user->created_at?->format('M d, Y') }}</td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button @click="$dispatch('open-modal', 'edit-user-{{ $user->id }}')" type="button" class="p-1.5 rounded-lg text-theme-muted hover:text-indigo-600 hover:bg-theme-main transition-colors" title="Edit User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        
                                        <button type="button" onclick="confirmDelete('{{ route('admin.users.destroy', $user->id) }}')" class="p-1.5 rounded-lg text-theme-muted hover:text-rose-600 hover:bg-theme-main transition-colors" title="Delete User">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>

                                    <!-- Edit User Modal -->
                                    <x-modal id="edit-user-{{ $user->id }}" title="Edit User #{{ $user->id }}">
                                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data" class="space-y-4 text-left">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Full Name</label>
                                                <input type="text" name="name" value="{{ $user->name }}" required class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Email Address</label>
                                                <input type="email" name="email" value="{{ $user->email }}" required class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Password (Leave blank to keep current)</label>
                                                <input type="password" name="password" class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Assign Roles</label>
                                                <div class="grid grid-cols-2 gap-2 mt-1">
                                                    @foreach($roles as $role)
                                                        <label class="flex items-center gap-2 p-2 rounded-lg bg-theme-main border border-theme cursor-pointer text-xs font-semibold">
                                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'checked' : '' }} class="rounded border-theme text-theme-primary">
                                                            <span>{{ ucfirst($role->name) }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Update Avatar</label>
                                                <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-theme-muted">
                                            </div>

                                            <div class="pt-3 flex justify-end gap-2">
                                                <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-xl bg-theme-primary text-white">Save Changes</button>
                                            </div>
                                        </form>
                                    </x-modal>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-theme-muted">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </x-card>
    </div>

    <!-- Create User Modal -->
    <x-modal id="create-user-modal" title="Create New Admin Staff / User">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Full Name</label>
                <input type="text" name="name" required placeholder="John Doe" class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
            </div>

            <div>
                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Email Address</label>
                <input type="email" name="email" required placeholder="john@example.com" class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
            </div>

            <div>
                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-3.5 py-2 text-sm rounded-xl bg-theme-main border border-theme text-theme-main">
            </div>

            <div>
                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Assign Roles</label>
                <div class="grid grid-cols-2 gap-2 mt-1">
                    @foreach($roles as $role)
                        <label class="flex items-center gap-2 p-2 rounded-lg bg-theme-main border border-theme cursor-pointer text-xs font-semibold">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-theme text-theme-primary">
                            <span>{{ ucfirst($role->name) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-theme-muted uppercase mb-1">Avatar Image</label>
                <input type="file" name="avatar" accept="image/*" class="w-full text-xs text-theme-muted">
            </div>

            <div class="pt-3 flex justify-end gap-2">
                <button type="submit" class="px-4 py-2 text-sm font-semibold rounded-xl bg-theme-primary text-white">Create Account</button>
            </div>
        </form>
    </x-modal>
</x-layouts.admin>
