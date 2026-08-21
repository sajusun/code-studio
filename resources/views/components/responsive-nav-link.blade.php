@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2.5 border-l-4 border-theme-primary text-xs font-bold text-theme-primary bg-theme-primary/10 rounded-r-xl transition-all duration-150 cursor-pointer'
            : 'block w-full px-4 py-2.5 border-l-4 border-transparent text-xs font-medium text-theme-muted hover:text-theme-main hover:bg-theme-main/5 hover:border-theme-muted rounded-r-xl transition-all duration-150 cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
