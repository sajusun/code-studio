<a href="{{ $href ?? url()->previous() == url()->current() ? route('admin.activity-logs.index') : url()->previous() }}"
    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150 ease-in-out">
    <i class="fa fa-arrow-left mr-2"></i> {{ $slot ?? 'Back' }}
</a>