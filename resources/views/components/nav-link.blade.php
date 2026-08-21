@props(['active' => false])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center gap-2 px-3 py-2 border-b-2 border-theme-primary text-xs font-bold text-theme-primary transition-all duration-150 cursor-pointer'
            : 'inline-flex items-center gap-2 px-3 py-2 border-b-2 border-transparent text-xs font-semibold text-theme-muted hover:text-theme-main hover:border-theme-muted transition-all duration-150 cursor-pointer';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
