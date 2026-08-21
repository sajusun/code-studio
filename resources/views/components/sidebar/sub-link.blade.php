@props([
    'href' => '#',
    'active' => false
])

@php
    $baseClasses = 'block py-2 px-3 text-xs font-semibold rounded-lg transition-all duration-200 cursor-pointer';
    $activeClasses = $active
        ? 'text-theme-primary font-bold bg-theme-primary/10'
        : 'text-theme-muted hover:text-theme-main hover:bg-theme-main/10';
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => "$baseClasses $activeClasses"]) }}>
    {{ $slot }}
</a>
