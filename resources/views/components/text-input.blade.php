@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-3.5 py-2.5 bg-theme-main border border-theme rounded-xl text-xs text-theme-main placeholder-theme-muted focus:outline-hidden focus:border-theme-primary focus:ring-1 focus:ring-theme-primary transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
