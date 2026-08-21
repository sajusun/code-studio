<header class="h-16 bg-theme-card border-b border-theme px-4 lg:px-6 flex items-center justify-between shrink-0 sticky top-0 z-30 shadow-xs transition-colors duration-200">
    <!-- Left Section: Sidebar Toggles & Breadcrumb / Search -->
    <div class="flex items-center gap-3">
        <!-- Desktop Sidebar Collapse Toggle Button -->
        <button @click="isCollapsed = !isCollapsed; localStorage.setItem('sidebar_collapsed', isCollapsed)" 
                type="button" 
                class="hidden md:flex p-2 rounded-xl text-theme-muted hover:text-theme-main hover:bg-theme-main/10 focus:outline-hidden transition-all duration-200" 
                :title="isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
            <svg class="w-5 h-5 transform transition-transform duration-300" :class="{ 'rotate-180': isCollapsed }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
        </button>

        <!-- Mobile Sidebar Drawer Toggle Button -->
        <button @click="mobileSidebarOpen = !mobileSidebarOpen" 
                type="button" 
                class="md:hidden p-2 rounded-xl text-theme-muted hover:text-theme-main hover:bg-theme-main/10 focus:outline-hidden transition-all duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Title / Brand Header -->
        <div class="flex items-center gap-2">
            <h1 class="text-base sm:text-lg font-bold text-theme-main leading-tight tracking-tight">Master Admin</h1>
            <span class="hidden sm:inline-block px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md bg-theme-primary/10 text-theme-primary">Pro</span>
        </div>
    </div>

    <!-- Right Section: Action Controls -->
    <div class="flex items-center gap-1.5 sm:gap-3">
        <!-- 1-Click Night Mode Toggle -->
        @include('layouts.partials.night-mode-toggle')

        <!-- Fullscreen Button -->
        @include('layouts.partials.fullscreen-button')

        <div class="h-6 w-px bg-theme border-r border-theme my-auto mx-1 hidden sm:block"></div>

        <!-- Notifications Dropdown -->
        @include('layouts.partials.notifications-dropdown')

        <!-- User Profile Dropdown -->
        @include('layouts.partials.profile-dropdown')
    </div>
</header>
