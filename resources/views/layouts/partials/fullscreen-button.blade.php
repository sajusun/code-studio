<div x-data="{ 
    isFullscreen: false,
    toggleFullscreen() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().then(() => {
                this.isFullscreen = true;
            }).catch(err => {
                console.error(`Error attempting to enable fullscreen: ${err.message}`);
            });
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen().then(() => {
                    this.isFullscreen = false;
                });
            }
        }
    }
}" 
x-init="
    document.addEventListener('fullscreenchange', () => {
        isFullscreen = !!document.fullscreenElement;
    });
">
    <button @click="toggleFullscreen()" 
            type="button" 
            class="p-2 rounded-xl text-theme-muted hover:text-theme-main hover:bg-theme-main/10 focus:outline-hidden transition-all duration-200" 
            :title="isFullscreen ? 'Exit Fullscreen' : 'Fullscreen'">
        
        <!-- Maximize Icon (Not Fullscreen) -->
        <svg x-show="!isFullscreen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-5h-4m4 0v4m0-4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
        </svg>
        
        <!-- Minimize Icon (Is Fullscreen) -->
        <svg x-show="isFullscreen" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9L4 4m0 0v4m0-4h4m11 5l5-5m0 0v4m0-4h-4M9 15l-5 5m0 0v-4m0 4h4m11-5l5 5m0 0v-4m0 4h-4"/>
        </svg>
    </button>
</div>
