@props(['activeTheme' => config('theme.default', 'emerald_slate')])

<div class="relative inline-block text-left" x-data="{ open: false, currentTheme: localStorage.getItem('theme') || '{{ $activeTheme }}' }" x-init="document.documentElement.setAttribute('data-theme', currentTheme)">
    <button @click="open = !open" type="button" class="inline-flex items-center gap-x-2 rounded-lg bg-theme-card px-3 py-2 text-sm font-semibold text-theme-main shadow-xs ring-1 ring-inset border-theme hover:bg-opacity-90">
        <svg class="w-4 h-4 text-theme-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-23" />
        </svg>
        <span>Theme</span>
        <svg class="w-4 h-4 text-theme-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
    </button>

    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 z-50 mt-2 w-56 origin-top-right rounded-xl bg-theme-card p-2 shadow-xl ring-1 ring-black/5 border-theme focus:outline-hidden" style="display: none;">
        <div class="text-xs font-bold text-theme-muted px-3 py-1 uppercase tracking-wider">Select Theme</div>
        
        <template x-for="(theme, key) in {
            'emerald_slate': 'Emerald Slate',
            'royal_indigo': 'Royal Indigo',
            'midnight_neon': 'Midnight Neon',
            'sunset_amber': 'Sunset Amber',
            'oceanic_breeze': 'Oceanic Breeze'
        }" :key="key">
            <button @click="
                currentTheme = key;
                document.documentElement.setAttribute('data-theme', key);
                localStorage.setItem('theme', key);
                open = false;
            " class="flex w-full items-center justify-between px-3 py-2 text-sm rounded-lg hover:bg-theme-main text-theme-main transition-colors">
                <span x-text="theme" :class="{ 'font-bold text-theme-primary': currentTheme === key }"></span>
                <span x-show="currentTheme === key" class="w-2 h-2 rounded-full bg-theme-primary"></span>
            </button>
        </template>
    </div>
</div>
