<div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700">
    <table {{ $attributes->merge(['class' => 'w-full text-sm text-left text-gray-500 dark:text-gray-400']) }}>
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700/50 dark:text-gray-400 border-b border-gray-100 dark:border-gray-700">
            <tr>
                {{ $thead }}
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            {{ $slot }}
        </tbody>
    </table>
</div>
