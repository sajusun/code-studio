@props([
    'href' => '#',
    'active' => false
])

@php
    $baseClasses = 'flex items-center gap-3 px-3 py-2.5 text-xs font-bold rounded-xl whitespace-nowrap transition-all duration-200 cursor-pointer group';
    $activeClasses = $active
        ? 'bg-theme-primary text-white shadow-md'
        : 'text-theme-muted hover:text-theme-main hover:bg-theme-main/10';
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => "$baseClasses $activeClasses"]) }}
   :class="isCollapsed ? 'lg:justify-center' : ''">
    @if(isset($icon))
        <div class="w-5 h-5 shrink-0 transition-colors duration-200">
            {{ $icon }}
        </div>
    @endif
    <span class="truncate transition-opacity duration-200" :class="isCollapsed ? 'lg:hidden' : ''">
        {{ $slot }}
    </span>
</a>
