@props([
    'href' => null
])

@if($href)
    <a 
        href="{{ $href }}" 
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900']) }}
    >
        {{ $slot ?? 'Cancel' }}
    </a>
@else
    <button 
        type="button" 
        {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900']) }}
    >
        {{ $slot ?? 'Cancel' }}
    </button>
@endif
