<a {{ $attributes->merge(['class' => 'flex items-center gap-2 w-full px-4 py-2 text-xs font-semibold text-theme-main hover:bg-theme-main/10 focus:outline-hidden transition-all duration-150 cursor-pointer']) }}>
    {{ $slot }}
</a>
