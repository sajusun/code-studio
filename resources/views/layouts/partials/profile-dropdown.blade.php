<div class="relative inline-block text-left" x-data="{ open: false }">
    @auth
        <!-- Profile Trigger Button -->
        <button @click="open = !open" 
                type="button" 
                class="flex items-center gap-3 p-1.5 rounded-xl hover:bg-theme-main/10 focus:outline-hidden transition-all duration-200 group cursor-pointer">
            
            <div class="relative shrink-0">
                <img src="{{ auth()->user()->profile?->avatar_url ?? auth()->user()->image }}" 
                     class="w-9 h-9 rounded-xl border border-theme object-cover shadow-xs group-hover:scale-105 transition-transform shrink-0" 
                     style="width: 36px; height: 36px; max-width: 36px; max-height: 36px; object-fit: cover;"
                     alt="{{ auth()->user()->name }}">
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-theme-card rounded-full"></span>
            </div>

            <div class="hidden md:block text-left min-w-0 max-w-[130px]">
                <p class="text-xs font-bold text-theme-main leading-tight group-hover:text-theme-primary transition-colors truncate">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-theme-muted font-semibold uppercase tracking-wider truncate">{{ auth()->user()->roles->first()?->name ?? 'Administrator' }}</p>
            </div>

            <svg class="w-4 h-4 text-theme-muted group-hover:text-theme-main transition-all transform shrink-0" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div x-show="open" 
             @click.away="open = false" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
             class="absolute right-0 z-50 mt-2 w-64 max-w-[calc(100vw-2rem)] origin-top-right rounded-2xl bg-theme-card border border-theme shadow-2xl p-2 focus:outline-hidden" 
             x-cloak>
            
            <!-- User Info Header -->
            <div class="px-3 py-3 mb-1 bg-theme-main/50 rounded-xl flex items-center gap-3">
                <img src="{{ auth()->user()->profile?->avatar_url ?? auth()->user()->image }}" 
                     class="w-10 h-10 rounded-xl border border-theme object-cover shrink-0" 
                     style="width: 40px; height: 40px; max-width: 40px; max-height: 40px; object-fit: cover;"
                     alt="{{ auth()->user()->name }}">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-theme-main truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-theme-muted truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>

            <!-- Links -->
            <div class="space-y-0.5">
                <a href="{{ route('admin.profile.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium {{ request()->routeIs('admin.profile.*') ? 'bg-theme-primary/10 text-theme-primary font-bold' : 'text-theme-main hover:bg-theme-main hover:text-theme-primary' }} transition-all">
                    <svg class="w-4 h-4 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    My Profile
                </a>
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-theme-primary/10 text-theme-primary font-bold' : 'text-theme-main hover:bg-theme-main hover:text-theme-primary' }} transition-all">
                    <svg class="w-4 h-4 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Account Settings
                </a>
                <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-medium {{ request()->routeIs('admin.activity-logs.*') ? 'bg-theme-primary/10 text-theme-primary font-bold' : 'text-theme-main hover:bg-theme-main hover:text-theme-primary' }} transition-all">
                    <svg class="w-4 h-4 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Activity Logs
                </a>
            </div>

            <!-- Divider -->
            <div class="my-1.5 border-t border-theme"></div>

            <!-- Logout -->
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-xs font-bold text-rose-500 hover:bg-rose-500/10 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    @endauth
</div>
