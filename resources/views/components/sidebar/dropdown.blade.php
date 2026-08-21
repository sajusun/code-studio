@props([
    'active' => false,
    'title' => ''
])

<div x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <button @click="open = !open"
            type="button"
            class="w-full flex items-center justify-between px-3 py-2.5 text-xs font-bold rounded-xl hover:bg-theme-main/10 transition-all duration-200 cursor-pointer group {{ $active ? 'text-theme-primary bg-theme-primary/10' : 'text-theme-muted hover:text-theme-main' }}"
            :class="isCollapsed ? 'lg:justify-center' : ''">
        <div class="flex items-center gap-3">
            @if(isset($icon))
                <div class="w-5 h-5 shrink-0 transition-colors duration-200">
                    {{ $icon }}
                </div>
            @endif
            <span class="truncate transition-opacity duration-200" :class="isCollapsed ? 'lg:hidden' : ''">
                {{ $title }}
            </span>
        </div>
        <svg class="w-4 h-4 transition-transform duration-200 shrink-0" :class="open ? 'rotate-90' : ''" x-show="!isCollapsed"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>
    <div x-show="open" class="space-y-1 mt-1 pl-8 pr-2" :class="isCollapsed ? 'lg:hidden' : ''" x-cloak>
        {{ $slot }}
    </div>
</div>
