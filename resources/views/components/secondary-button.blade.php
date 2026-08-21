<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-theme-main border border-theme rounded-xl font-bold text-xs text-theme-main hover:bg-theme-main/80 focus:outline-hidden transition-all duration-150 cursor-pointer']) }}>
    {{ $slot }}
</button>
