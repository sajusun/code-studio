<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-theme-primary text-white border border-transparent rounded-xl font-bold text-xs uppercase tracking-wider hover:bg-opacity-90 focus:outline-hidden transition-all duration-150 cursor-pointer shadow-sm']) }}>
    {{ $slot }}
</button>
