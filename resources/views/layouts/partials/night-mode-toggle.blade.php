<div x-data="{ 
    isDarkMode: localStorage.getItem('theme') === 'midnight_neon' || (localStorage.getItem('theme') === null && window.matchMedia('(prefers-color-scheme: dark)').matches),
    toggleNightMode() {
        this.isDarkMode = !this.isDarkMode;
        const newTheme = this.isDarkMode ? 'midnight_neon' : 'emerald_slate';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        window.dispatchEvent(new CustomEvent('theme-changed', { detail: newTheme }));
    }
}"
x-init="
    const currentTheme = localStorage.getItem('theme') || (isDarkMode ? 'midnight_neon' : 'emerald_slate');
    document.documentElement.setAttribute('data-theme', currentTheme);
    isDarkMode = currentTheme === 'midnight_neon';
">
    <button @click="toggleNightMode()" 
            type="button" 
            class="p-2 rounded-xl text-theme-muted hover:text-theme-main hover:bg-theme-main/10 focus:outline-hidden transition-all duration-200" 
            :title="isDarkMode ? 'Switch to Light Mode' : 'Switch to Dark/Night Mode'">
        
        <!-- Sun Icon (Active when dark mode to switch to light) -->
        <svg x-show="isDarkMode" x-cloak class="w-5 h-5 text-amber-400 transform hover:rotate-45 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>

        <!-- Moon Icon (Active when light mode to switch to dark) -->
        <svg x-show="!isDarkMode" class="w-5 h-5 text-slate-600 hover:text-slate-900 transform hover:-rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
    </button>
</div>
