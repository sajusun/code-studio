@props(['id', 'title' => 'Modal Dialog'])

<div x-data="{ open: false }" 
     x-show="open" 
     @open-modal.window="if ($event.detail === '{{ $id }}') open = true"
     @close-modal.window="if ($event.detail === '{{ $id }}') open = false"
     @keydown.escape.window="open = false" 
     class="relative z-50" 
     style="display: none;">
    
    <div x-show="open" x-transition.opacity class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
        <div x-show="open" @click.away="open = false" x-transition.scale.95 class="mx-auto max-w-xl rounded-2xl bg-theme-card border border-theme p-6 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-theme">
                <h3 class="text-lg font-bold text-theme-main">{{ $title }}</h3>
                <button @click="open = false" class="text-theme-muted hover:text-theme-main">&times;</button>
            </div>
            
            <div class="mt-4">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
