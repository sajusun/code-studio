<button 
    type="submit" 
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 disabled:opacity-50']) }}
>
    {{ $slot ?? 'Submit' }}
</button>
