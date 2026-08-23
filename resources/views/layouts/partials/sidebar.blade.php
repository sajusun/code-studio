<!-- Sidebar Container -->
<div>
    <!-- Desktop Collapsible Sidebar -->
    <aside class="hidden md:flex flex-col justify-between shrink-0 bg-theme-sidebar border-r border-theme py-6 transition-all duration-300 h-screen sticky top-0 z-40 select-none overflow-x-hidden"
           :class="isCollapsed ? 'w-20 px-3' : 'w-64 px-4'">
        
        <div>
            <!-- App Brand / Logo -->
            <div class="flex items-center gap-3 mb-8 px-1 overflow-hidden" :class="isCollapsed ? 'justify-center' : 'px-2'">
                <div class="w-10 h-10 rounded-2xl bg-theme-primary flex items-center justify-center font-black text-white text-xl shadow-lg shrink-0 transform hover:scale-105 transition-transform">
                    M
                </div>
                <div x-show="!isCollapsed" x-transition.opacity class="min-w-0">
                    <h2 class="font-extrabold text-white text-base leading-tight truncate">Master Admin</h2>
                    <p class="text-[11px] text-theme-sidebar font-medium truncate">Headless Microservice</p>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="space-y-1.5">
                <!-- Dashboard Link -->
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }} transition-all group relative"
                   :class="isCollapsed ? 'justify-center' : ''">
                    <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="!isCollapsed" x-transition.opacity class="truncate">Dashboard</span>
                    
                    <!-- Tooltip when collapsed -->
                    <span x-show="isCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50">
                        Dashboard
                    </span>
                </a>

                <!-- Product List Link -->
                <a href="{{ route('admin.products.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.products.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }} transition-all group relative"
                   :class="isCollapsed ? 'justify-center' : ''">
                    <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span x-show="!isCollapsed" x-transition.opacity class="truncate">Product List</span>
                    
                    <span x-show="isCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50">
                        Product List
                    </span>
                </a>

                <!-- User & Staff Link -->
                <a href="{{ route('admin.users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.users.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }} transition-all group relative"
                   :class="isCollapsed ? 'justify-center' : ''">
                    <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="!isCollapsed" x-transition.opacity class="truncate">User & Staff</span>
                    
                    <!-- Tooltip -->
                    <span x-show="isCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50">
                        User & Staff
                    </span>
                </a>

                <!-- Roles & Permissions Link -->
                <a href="{{ route('admin.roles.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.roles.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }} transition-all group relative"
                   :class="isCollapsed ? 'justify-center' : ''">
                    <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span x-show="!isCollapsed" x-transition.opacity class="truncate">Roles & Permissions</span>
                    
                    <!-- Tooltip -->
                    <span x-show="isCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50">
                        Roles & Permissions
                    </span>
                </a>

                <!-- Broadcast Test Link -->
                <a href="{{ route('admin.broadcast-test.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.broadcast-test.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }} transition-all group relative"
                   :class="isCollapsed ? 'justify-center' : ''">
                    <svg class="w-5 h-5 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
                    </svg>
                    <span x-show="!isCollapsed" x-transition.opacity class="truncate">Broadcast Test</span>
                    
                    <!-- Tooltip -->
                    <span x-show="isCollapsed" class="absolute left-full ml-3 px-2.5 py-1 bg-slate-900 text-white text-xs rounded-lg shadow-lg whitespace-nowrap opacity-0 group-hover:opacity-100 pointer-events-none transition-opacity z-50">
                        Broadcast Test
                    </span>
                </a>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="border-t border-white/10 pt-4 space-y-3" :class="isCollapsed ? 'px-1 text-center' : 'px-3'">
            @auth
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3.5 px-3 py-2.5 rounded-2xl text-xs font-bold text-rose-400 hover:bg-rose-500/10 transition-all group relative"
                            :class="isCollapsed ? 'justify-center' : ''"
                            title="Sign Out">
                        <svg class="w-4 h-4 shrink-0 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span x-show="!isCollapsed" x-transition.opacity class="truncate">Sign Out</span>
                    </button>
                </form>
            @endauth

            <div x-show="!isCollapsed" x-transition.opacity class="text-[11px] text-theme-sidebar font-medium leading-tight">
                <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }}</p>
                <p class="text-white/40 mt-0.5">PHP v{{ PHP_VERSION }}</p>
            </div>
        </div>
    </aside>

    <!-- Mobile Off-Canvas Drawer Overlay -->
    <div x-show="mobileSidebarOpen" 
         x-cloak
         class="fixed inset-0 z-50 md:hidden flex">
        
        <!-- Backdrop Overlay (No blur to keep dashboard clean) -->
        <div @click="mobileSidebarOpen = false" 
             x-show="mobileSidebarOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-slate-900/60"></div>

        <!-- Drawer Content -->
        <aside class="relative w-72 bg-theme-sidebar border-r border-theme p-6 flex flex-col justify-between h-full z-10 shadow-2xl"
               x-show="mobileSidebarOpen"
               x-transition:enter="transition ease-out duration-300 transform"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-200 transform"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full">
            
            <div>
                <!-- Brand Header + Close Button -->
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-theme-primary flex items-center justify-center font-black text-white text-xl shadow-lg">
                            M
                        </div>
                        <div>
                            <h2 class="font-extrabold text-white text-base leading-tight">Master Admin</h2>
                            <p class="text-[11px] text-theme-sidebar font-medium">Headless Microservice</p>
                        </div>
                    </div>
                    <button @click="mobileSidebarOpen = false" class="p-2 rounded-xl text-theme-sidebar hover:text-white hover:bg-white/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Menu Items -->
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 00-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.products.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Product List
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.users.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        User & Staff
                    </a>
                    <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.roles.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Roles & Permissions
                    </a>
                    <a href="{{ route('admin.broadcast-test.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl font-semibold text-sm {{ request()->routeIs('admin.broadcast-test.*') ? 'bg-theme-primary text-white shadow-md' : 'text-theme-sidebar hover:text-white hover:bg-white/10' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/></svg>
                        Broadcast Test
                    </a>
                </nav>
            </div>

            <!-- Footer Signout -->
            <div class="border-t border-white/10 pt-4">
                @auth
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-bold text-rose-400 hover:bg-rose-500/10 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Sign Out
                        </button>
                    </form>
                @endauth
            </div>
        </aside>
    </div>
</div>
