<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-4 py-2 bg-rose-600 border border-transparent rounded-xl font-bold text-xs text-white uppercase tracking-wider hover:bg-rose-500 active:bg-rose-700 focus:outline-hidden transition-all duration-150 cursor-pointer shadow-sm']) }}>
    {{ $slot }}
</button>
